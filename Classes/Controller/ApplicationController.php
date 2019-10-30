<?php

	namespace ITX\Jobs\Controller;

	/***
	 *
	 * This file is part of the "Jobs" Extension for TYPO3 CMS.
	 *
	 * For the full copyright and license information, please read the
	 * LICENSE.txt file that was distributed with this source code.
	 *
	 *  (c) 2019 Stefanie Döll, it.x informationssysteme gmbh
	 *           Benjamin Jasper, it.x informationssysteme gmbh
	 *
	 ***/

	use ITX\Jobs\Domain\Model\Posting;
	use TYPO3\CMS\Core\Messaging\FlashMessage;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

	/**
	 * ApplicationController
	 */
	class ApplicationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
	{

		/**
		 * applicationRepository
		 *
		 * @var \ITX\Jobs\Domain\Repository\ApplicationRepository
		 * @inject
		 */
		protected $applicationRepository = null;

		protected $fileSizeLimit;

		const APP_FILE_FOLDER = "applications/";

		/**
		 * initialize create action
		 * adjusts date time format to y-m-d
		 *
		 * @param void
		 *
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
		 */
		public function initializeCreateAction()
		{
			$this->fileSizeLimit = GeneralUtility::getMaxUploadFileSize();
			$this->arguments->getArgument('newApplication')
							->getPropertyMappingConfiguration()->forProperty('earliestDateOfJoining')
							->setTypeConverterOption(
								'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
								\TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT,
								'Y-m-d'
							);
		}

		/**
		 * action new
		 *
		 * @return void
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
		 */
		public function newAction()
		{
			if($this->request->getPluginName() == "DetailView") {
				$this->view->assign("settings", $this->request->getArgument("settings"));
			}
			if(!$this->request->hasArgument("apply")) {
				if($this->request->hasArgument('appForm')) {
					$appFormUid = $this->request->getArgument("appForm");
				} else {
					$appFormUid = $this->settings['applicationFormUid'];
				}
				if($appFormUid) {
					$this->uriBuilder->reset()->setTargetPageUid($appFormUid);
					$uri = $this->uriBuilder->uriFor('new', array(
						'postingUid' => $this->request->getArgument("postingUid"),
						'postingTitle' => $this->request->getArgument("postingTitle"),
						'apply' => '1',
						'settings' => $this->request->getArgument("settings")), 'Application', null, "ApplicationForm");
					$this->redirectToUri($uri);
				}
			} else {
				$this->settings = $this->request->getArgument("settings");
				$this->view->assign("settings", $this->settings);
			}

			$this->fileSizeLimit = GeneralUtility::getMaxUploadFileSize();
			$postingUid = $this->request->getArgument("postingUid");
			$title = $this->request->getArgument("postingTitle");

			if ($this->request->hasArgument("fileError"))
			{
				$error = $this->request->getArgument("fileError");
				$this->view->assign("fileError", $error);
			}
			else
			{
				$this->view->assign("fileError", 0);
			}
			$this->view->assign("postingUid", $postingUid);
			$this->view->assign("postingTitle", $title);
			$this->view->assign("fileSizeLimit", strval($this->fileSizeLimit)/1024);
		}

		/**
		 * action create
		 *
		 * @param \ITX\Jobs\Domain\Model\Application $newApplication
		 *
		 * @return void
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
		 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
		 */
		public function createAction(\ITX\Jobs\Domain\Model\Application $newApplication)
		{
			//Uploads in order as defined in Domain Model
			$uploads = array("cv", "cover_letter", "testimonials", "other_files");

			//get additional infos
			$postingTitle = $this->request->getArgument("postingTitle");
			$postingUid = $this->request->getArgument("postingUid");
			$this->settings = $this->request->getArgument("settings");

			//Check if $_FILES Entries have errors
			foreach ($uploads as $upload)
			{
				//Check if Filetype is accepted
				if ($_FILES['tx_jobs_frontend']['type'][$upload] != "application/pdf" && $_FILES['tx_jobs_frontend']['type'][$upload] != "")
				{
					$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('fe.error.fileType', 'jobs'), null, \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
					$this->redirect("new", "Application", null, array(
						"postingUid" => $postingUid,
						"postingTitle" => $postingTitle,
						"fileError" => $upload
					));
					return;
				}

				$errorcode = $_FILES['tx_jobs_frontend']['error'][$upload];
				if (intval($errorcode) == 1)
				{
					$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('fe.error.fileSize', 'jobs', array("0"=> intval($this->fileSizeLimit)/1024)), null, \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
					$this->redirect("new", "Application", null, array(
						"postingUid" => $postingUid,
						"postingTitle" => $postingTitle,
						"fileError" => $upload
					));
					return;
				}
			}

			$newApplication->setPosting($postingUid);
			$this->applicationRepository->add($newApplication);

			// Processing files
			if ($_FILES['tx_jobs_frontend']['name']['cv'])
			{
				$movedNewFile = $this->handleFileUpload("cv", $newApplication);
				$this->buildRelations($newApplication->getUid(), $movedNewFile, 'cv', 'tx_jobs_domain_model_application', $newApplication->getPid());
			}
			if ($_FILES['tx_jobs_frontend']['name']['cover_letter'])
			{
				$movedNewFile = $this->handleFileUpload("cover_letter", $newApplication);
				$this->buildRelations($newApplication->getUid(), $movedNewFile, 'cover_letter', 'tx_jobs_domain_model_application', $newApplication->getPid());
			}
			if ($_FILES['tx_jobs_frontend']['name']['testimonials'])
			{
				$movedNewFile = $this->handleFileUpload("testimonials", $newApplication);
				$this->buildRelations($newApplication->getUid(), $movedNewFile, 'testimonials', 'tx_jobs_domain_model_application', $newApplication->getPid());
			}
			if ($_FILES['tx_jobs_frontend']['name']['other_files'])
			{
				$movedNewFile = $this->handleFileUpload("cv", $newApplication);
				$this->buildRelations($newApplication->getUid(), $movedNewFile, 'cv', 'tx_jobs_domain_model_application', $newApplication->getPid());
			}
			$uri = $this->uriBuilder->reset()
									->setTargetPageUid($this->settings["successPage"])
									->setCreateAbsoluteUri(TRUE)
									->build();
			$this->redirectToUri($uri);
		}

		/**
		 * Attaches existing File to Domain Model Record
		 *
		 * @param $newStorageUid ;UID of Record or Domain Model the file will attach to
		 * @param $file          ;from Objectmanagers storage repository
		 * @param $field         ;fieldname as named in tca file
		 * @param $table         ;table tca domain table name e.g. tx_<extensionName>_domain_model_<domainModelName>
		 * @param $newStoragePid ;PID of Record or Domain Model the file will attach to
		 */
		private function buildRelations($newStorageUid, $file, $field, $table, $newStoragePid)
		{

			$data = array();
			$data['sys_file_reference']['NEW1234'] = array(
				'uid_local' => $file->getUid(),
				'uid_foreign' => $newStorageUid, // uid of your content record or own model
				'tablenames' => $table, //tca table name
				'fieldname' => $field, //see tca for fieldname
				'pid' => $newStoragePid,
				'table_local' => 'sys_file',
			);
			$data[$table][$newStorageUid] = array(
				$pid => $storagePid,
				$field => 'NEW1234'
			);

			/** @var \TYPO3\CMS\Core\DataHandling\DataHandler $tce */
			$tce = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\DataHandling\DataHandler'); // create TCE instance
			$tce->start($data, array());
			$tce->process_datamap();
		}

		/**
		 * @param $fieldName
		 * @param $domainObject \ITX\Jobs\Domain\Model\Application
		 *
		 * @return mixed
		 */
		private function handleFileUpload($fieldName, \ITX\Jobs\Domain\Model\Application $domainObject)
		{

			$folder = self::APP_FILE_FOLDER.$domainObject->getFirstName()."_".$domainObject->getLastName()."_id_".$domainObject->getPosting();

			//be careful - you should validate the file type! This is not included here
			$tmpName = $_FILES['tx_jobs_frontend']['name'][$fieldName];
			$tmpFile = $_FILES['tx_jobs_frontend']['tmp_name'][$fieldName];

			$storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
			$storage = $storageRepository->findByUid('1'); //this is the fileadmin storage

			//build the new storage folder
			if ($storage->hasFolder($folder))
			{
				$targetFolder = $storage->getFolder($folder);
			}
			else
			{
				$targetFolder = $storage->createFolder($folder);
			}

			//file name, be sure that this is unique
			$newFileName = $fieldName."_".$domainObject->getFirstName()."_".$domainObject->getLastName()."_id_".$domainObject->getPosting();

			//build sys_file
			$movedNewFile = $storage->addFile($tmpFile, $targetFolder, $newFileName);
			$this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();

			return $movedNewFile;
		}
	}