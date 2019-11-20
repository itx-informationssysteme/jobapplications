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
	use ITX\Jobs\PageTitle\JobsPageTitleProvider;
	use ScssPhp\ScssPhp\Formatter\Debug;
	use TYPO3\CMS\Core\Messaging\FlashMessage;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
	use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

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
		 * @var \ITX\Jobs\Domain\Repository\PostingRepository
		 * @inject
		 */
		private $postingRepository;

		/**
		 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
		 * @inject
		 */
		protected $persistenceManager;

		protected $logger = null;

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
			/** @var $logger \TYPO3\CMS\Core\Log\Logger */
			$this->logger = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);
		}

		/**
		 * action new
		 *
		 * @return void
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
		 */
		public function newAction()
		{
			$this->fileSizeLimit = GeneralUtility::getMaxUploadFileSize();
			$posting = $this->request->getArgument("posting");
			$postingObject = $this->postingRepository->findByUid($posting);

			if ($this->request->hasArgument("fileError"))
			{
				$error = $this->request->getArgument("fileError");
				$this->view->assign("fileError", $error);
			}
			else
			{
				$this->view->assign("fileError", 0);
			}

			$titleProvider = GeneralUtility::makeInstance(JobsPageTitleProvider::class);

			$title = $this->settings["pageTitle"];
			if ($title != "")
			{
				$title = str_replace("%postingTitle%", $postingObject->getTitle(), $title);
			} else {
				$title = $postingObject->getTitle();
			}

			$titleProvider->setTitle($title);
			$this->view->assign('posting', $posting);

			$this->view->assign('posting', $postingObject);
			$this->view->assign("fileSizeLimit", strval($this->fileSizeLimit) / 1024);
		}

		/**
		 * action create
		 *
		 * @param \ITX\Jobs\Domain\Model\Application $newApplication
		 *
		 * @return void
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
		 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException
		 */
		public function createAction(\ITX\Jobs\Domain\Model\Application $newApplication)
		{
			//Uploads in order as defined in Domain Model
			$uploads = array("cv", "cover_letter", "testimonials", "other_files");

			//get posting
			$posting = $this->request->getArgument("posting");

			//Check if $_FILES Entries have errors
			foreach ($uploads as $upload)
			{
				//Check if Filetype is accepted
				if ($_FILES['tx_jobs_frontend']['type'][$upload] != "application/pdf" && $_FILES['tx_jobs_frontend']['type'][$upload] != "")
				{
					$this->addFlashMessage(LocalizationUtility::translate('fe.error.fileType', 'jobs'), null, \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
					$this->redirect("new", "Application", null, array(
						"postingUid" => $posting,
						"postingTitle" => $postingTitle,
						"fileError" => $upload
					));

					return;
				}

				$errorcode = $_FILES['tx_jobs_frontend']['error'][$upload];
				if (intval($errorcode) == 1)
				{
					$this->addFlashMessage(LocalizationUtility::translate('fe.error.fileSize', 'jobs', array("0" => intval($this->fileSizeLimit) / 1024)), null, \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
					$this->redirect("new", "Application", null, array(
						"postingUid" => $posting,
						"postingTitle" => $postingTitle,
						"fileError" => $upload
					));

					return;
				}
			}

			$newApplication->setPosting($posting);
			$newApplication->setPid($this->settings["saveInFolderPid"]);
			$this->applicationRepository->add($newApplication);
			$this->persistenceManager->persistAll();

			// Processing files
			if ($_FILES['tx_jobs_applicationform']['name']['cv'])
			{
				$movedNewFileCv = $this->handleFileUpload("cv", $newApplication);
				$this->buildRelations($newApplication->getUid(), $movedNewFileCv, 'cv', 'tx_jobs_domain_model_application', $newApplication->getPid());
			}
			if ($_FILES['tx_jobs_applicationform']['name']['cover_letter'])
			{
				$movedNewFileCover = $this->handleFileUpload("cover_letter", $newApplication);
				$this->buildRelations($newApplication->getUid(), $movedNewFileCover, 'cover_letter', 'tx_jobs_domain_model_application', $newApplication->getPid());
			}
			if ($_FILES['tx_jobs_applicationform']['name']['testimonials'])
			{
				$movedNewFileTestimonial = $this->handleFileUpload("testimonials", $newApplication);
				$this->buildRelations($newApplication->getUid(), $movedNewFileTestimonial, 'testimonials', 'tx_jobs_domain_model_application', $newApplication->getPid());
			}
			if ($_FILES['tx_jobs_applicationform']['name']['other_files'])
			{
				$movedNewFileOther = $this->handleFileUpload("other_files", $newApplication);
				$this->buildRelations($newApplication->getUid(), $movedNewFileOther, 'other_files', 'tx_jobs_domain_model_application', $newApplication->getPid());
			}

			//Mail Handling

			$objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

			$currentPosting = $this->postingRepository->findByUid($newApplication->getPosting());
			$contact = $currentPosting->getContact();

			$salutation = LocalizationUtility::translate("fe.application.selector.".$newApplication->getSalutation(), "jobs");

			if ($this->settings["sendEmailToContact"] || $this->settings['sendEmailToInternal'])
			{
				$mail = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\MailMessage::class);
				// Prepare and send the message
				$mail
					// Give the message a subject
					->setSubject(LocalizationUtility::translate("fe.email.toContactSubject", 'jobs', array(0 => $currentPosting->getTitle())))

					// Set the From address with an associative array
					->setFrom(array($newApplication->getEmail() => $newApplication->getFirstName()." ".$newApplication->getLastName()))

					// Give it a body
					->setBody('Name: '.$salutation.' '.$newApplication->getFirstName().' '.$newApplication->getLastName().'<br>'.
							  'E-Mail: '.$newApplication->getEmail().'<br>'.
							  'Telephone: '.$newApplication->getPhone().'<br>'.
							  'Address: '.$newApplication->getAddressStreetAndNumber().'<br>'.$newApplication->getAddressAddition().'<br>'.
							  $newApplication->getAddressPostCode().' '.$newApplication->getAddressCity().'<br>'.$newApplication->getAddressCountry());

				$files = array($movedNewFileCv, $movedNewFileCover, $movedNewFileTestimonial, $movedNewFileOther);
				foreach ($files as $file)
				{
					if ($file)
					{
						$mail->attach(\Swift_Attachment::fromPath($file->getPublicUrl()));
					}
				}

				//Figure out who the email will be sent to and how
				if ($this->settings['sendEmailToInternal'] && $this->settings['sendEmailToContact'])
				{
					$mail->setTo(array($contact->getEmail() => $contact->getFirstName()." ".$contact->getLastName()));
					$mail->setBcc($this->settings['sendEmailToInternal']);
				}
				elseif (!$this->settings['sendEmailToContact'] && $this->settings['sendEmailToInternal'])
				{
					$mail->setTo(array($this->settings['sendEmailToInternal'] => 'Internal'));
				}
				elseif ($this->settings['sendEmailToContact'] && !$this->settings['sendEmailToInternal'])
				{
					$mail->setTo(array($contact->getEmail() => $contact->getFirstName()." ".$contact->getLastName()));
				}

				try
				{
					$mail->send();
				}
				catch (Exception $e)
				{
					$this->logger->log(\TYPO3\CMS\Core\Log\LogLevel::CRITICAL, "Error trying to send a mail: ".$e->getMessage(), array($this->settings, $mail));
				}
			}

			if ($this->settings["sendEmailToApplicant"])
			{
				$mail = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\MailMessage::class);

				//Template Messages
				$subject = $this->settings['sendEmailToApplicantSubject'];
				$subject = str_replace("%postingTitle%", $currentPosting->getTitle(), $subject);

				$body = $this->settings["sendEmailToApplicantText"];
				switch (intval($newApplication->getSalutation()))
				{
					case 3:
					case 0:
						$salutation = "";
						break;
					case 1:
						$salutation = LocalizationUtility::translate("fe.application.selector.mr", "jobs");
						break;
					case 2:
						$salutation = LocalizationUtility::translate("fe.application.selector.mrs", "jobs");
						break;
				}
				$body = str_replace("%applicantSalutation%", $salutation, $body);
				$body = str_replace("%applicantFirstname%", $newApplication->getFirstName(), $body);
				$body = str_replace("%applicantLastname%", $newApplication->getLastName(), $body);
				$body = str_replace("%postingTitle%", $currentPosting->getTitle(), $body);

				// Prepare and send the message
				$mail
					// Give the message a subject
					->setSubject($subject)

					// Set the From address with an associative array
					->setFrom(array($this->settings["emailSender"] => $this->settings["emailSenderName"]))
					->setTo(array($newApplication->getEmail() => $newApplication->getFirstName()." ".$newApplication->getLastName()))

					// Give it a body
					->setBody($body);

				try
				{
					$mail->send();
				}
				catch (Exception $e)
				{
					$this->logger->log(\TYPO3\CMS\Core\Log\LogLevel::CRITICAL, "Error trying to send a mail: ".$e->getMessage(), array($this->settings, $mail));
				}
			}

			if(!$this->settings['saveApplicationInBackend'])
			{
				$storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
				$storage = $storageRepository->findByUid('1');
				if($storage->hasFolder($this->getApplicantFolder($newApplication)))
				{
					$folder = $storage->getFolder($this->getApplicantFolder($newApplication));
				}
				$this->applicationRepository->remove($newApplication);
				if ($folder) {
					$storage->deleteFolder($folder, true);
				}
			}

			$uri = $this->uriBuilder->reset()
									->setTargetPageUid($this->settings["successPage"])
									->setCreateAbsoluteUri(true)
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
			$tce = GeneralUtility::makeInstance('TYPO3\CMS\Core\DataHandling\DataHandler'); // create TCE instance
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

			$folder = $this->getApplicantFolder($domainObject);

			//be careful - you should validate the file type! This is not included here
			$tmpName = $_FILES['tx_jobs_applicationform']['name'][$fieldName];
			$tmpFile = $_FILES['tx_jobs_applicationform']['tmp_name'][$fieldName];

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
			$newFileName = $fieldName."_".$domainObject->getFirstName()."_".$domainObject->getLastName()."_id_".$domainObject->getPosting().".pdf";

			//build sys_file
			$movedNewFile = $storage->addFile($tmpFile, $targetFolder, $newFileName);
			$this->persistenceManager->persistAll();

			return $movedNewFile;
		}

		/**
		 * Helper function to generate the folder for an application
		 *
		 * @param $applicationObject
		 *
		 * @return string
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException
		 */
		private function getApplicantFolder($applicationObject) {
			return self::APP_FILE_FOLDER.(new \TYPO3\CMS\Core\Resource\Driver\LocalDriver)
				->sanitizeFileName($applicationObject->getFirstName()."_".$applicationObject->getLastName()."_id_".$applicationObject->getPosting());
		}
	}