<?php

	namespace ITX\Jobapplications\Controller;

	/***************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2020
	 *  All rights reserved
	 *
	 *  This script is part of the TYPO3 project. The TYPO3 project is
	 *  free software; you can redistribute it and/or modify
	 *  it under the terms of the GNU General Public License as published by
	 *  the Free Software Foundation; either version 3 of the License, or
	 *  (at your option) any later version.
	 *
	 *  The GNU General Public License can be found at
	 *  http://www.gnu.org/copyleft/gpl.html.
	 *
	 *  This script is distributed in the hope that it will be useful,
	 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
	 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 *  GNU General Public License for more details.
	 *
	 *  This copyright notice MUST APPEAR in all copies of the script!
	 ***************************************************************/

	use ITX\Jobapplications\Domain\Model\Application;
	use ITX\Jobapplications\Domain\Model\Posting;
	use ITX\Jobapplications\Domain\Model\Status;
	use ITX\Jobapplications\PageTitle\JobsPageTitleProvider;
	use ScssPhp\ScssPhp\Formatter\Debug;
	use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
	use TYPO3\CMS\Core\Database\ConnectionPool;
	use TYPO3\CMS\Core\Messaging\FlashMessage;
	use TYPO3\CMS\Core\Resource\FileInterface;
	use TYPO3\CMS\Core\Resource\ResourceFactory;
	use TYPO3\CMS\Core\Utility\DebugUtility;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Extbase\Domain\Model\FileReference;
	use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
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
		 * @var \ITX\Jobapplications\Domain\Repository\ApplicationRepository
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		protected $applicationRepository = null;

		protected $fileSizeLimit;

		/**
		 * @var \ITX\Jobapplications\Domain\Repository\PostingRepository
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		private $postingRepository;

		/**
		 * @var \ITX\Jobapplications\Domain\Repository\StatusRepository
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		private $statusRepository;

		/**
		 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		protected $persistenceManager;

		/**
		 * @var \ITX\Jobapplications\Service\ApplicationFileService
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		protected $applicationFileService;

		/**
		 * signalSlotDispatcher
		 *
		 * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		protected $signalSlotDispatcher;

		/**
		 * @var \TYPO3\CMS\Core\Log\Logger
		 */
		protected $logger = null;

		/** @var boolean */
		protected $isLegacy;

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
			$this->arguments->getArgument('newApplication')
							->getPropertyMappingConfiguration()->forProperty('earliestDateOfJoining')
							->setTypeConverterOption(
								'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
								\TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT,
								'Y-m-d'
							);

			$this->logger = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);
		}

		/**
		 * Initializes the view before invoking an action method.
		 *
		 * Override this method to solve assign variables common for all actions
		 * or prepare the view in another way before the action is called.
		 *
		 * @param ViewInterface $view The view to be initialized
		 */
		public function initializeView(ViewInterface $view)
		{
			if (is_object($GLOBALS['TSFE']))
			{
				$view->assign('pageData', $GLOBALS['TSFE']->page);
			}
			parent::initializeView($view);
		}

		/**
		 * initialize create action
		 *
		 * @param void
		 */
		public function initializeAction()
		{
			$this->fileSizeLimit = GeneralUtility::getMaxUploadFileSize();
		}

		/**
		 * action new
		 *
		 * @param ITX\Jobapplications\Domain\Model\Posting $posting
		 *
		 * @return void
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
		 */
		public function newAction(Posting $posting = null)
		{
			/*
			Getting posting when Detailview and applicationform are on the same page.
			Limited to posting via GET Variable which isn't the best way of.
			Might need to find a better solution in the future
			*/
			if ($posting === null && $_REQUEST['postingApp'])
			{
				$postingUid = $_REQUEST['postingApp'];
				$posting = $this->postingRepository->findByUid($postingUid);
			}

			if ($posting instanceof Posting)
			{
				$titleProvider = GeneralUtility::makeInstance(JobsPageTitleProvider::class);

				$title = $this->settings["pageTitle"];
				if (!empty($title))
				{
					$title = str_replace("%postingTitle%", $posting->getTitle(), $title);
				}
				else
				{
					$title = $posting->getTitle();
				}

				$titleProvider->setTitle($title);

				$this->view->assign('posting', $posting);
			}

			$this->view->assign("fileSizeLimit", strval($this->fileSizeLimit) / 1024);

			if ($this->request->hasArgument("fileError"))
			{
				$error = $this->request->getArgument("fileError");
				$this->view->assign("fileError", $error);
			}
			else
			{
				$this->view->assign("fileError", 0);
			}
		}

		/**
		 * success Action
		 *
		 * @param string $firstName
		 * @param string $lastName
		 * @param string $salutation
		 */
		public function successAction(string $firstName, string $lastName, string $salutation, int $postingUid = -1)
		{
			$salutationValue = $salutation;

			if ($salutation == "div" || $salutation == "")
			{
				$salutation = $firstName;
			}
			else
			{
				$salutation = LocalizationUtility::translate("fe.application.selector.".$salutation, "jobapplications");
			}

			$posting = $this->postingRepository->findByUid($postingUid);

			$this->view->assign("firstName", $firstName);
			$this->view->assign("lastName", $lastName);
			$this->view->assign("salutation", $salutation);
			$posting ? $this->view->assign("salutationValue", $posting) : false;
		}

		/**
		 * create Action
		 *
		 * @param \ITX\Jobapplications\Domain\Model\Application $newApplication
		 * @param \ITX\Jobapplications\Domain\Model\Posting     $posting
		 *
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
		 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
		 * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
		 * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
		 */
		public function createAction(\ITX\Jobapplications\Domain\Model\Application $newApplication, \ITX\Jobapplications\Domain\Model\Posting $posting = null)
		{
			$allowedFileTypes = [
				"application/pdf"
			];

			$multiFileUpload = $_FILES['tx_jobapplications_applicationform']['name']['upload'];

			if (is_array($multiFileUpload))
			{
				$amountOfFiles = count($multiFileUpload);
			}
			else
			{
				$amountOfFiles = 0;
			}

			// Single file upload fields
			if ($amountOfFiles === 0)
			{
				//Uploads in order as defined in Domain Model
				$uploads = array("cv", "cover_letter", "testimonials", "other_files");

				//Check if $_FILES Entries have errors
				foreach ($uploads as $upload)
				{
					//Check if Filetype is accepted
					if (!in_array($_FILES['tx_jobapplications_applicationform']['type'][$upload], $allowedFileTypes) && $_FILES['tx_jobapplications_applicationform']['type'][$upload] != "")
					{
						if (!in_array($_FILES['tx_jobapplications_applicationform']['type'][$upload], $allowedFileTypes))
						{
							$this->addFlashMessage(LocalizationUtility::translate('fe.error.fileType', 'jobapplications'), null, \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
						}
						else
						{
							$this->addFlashMessage(LocalizationUtility::translate('fe.error.fileSize', 'jobapplications', array("0" => intval($this->fileSizeLimit) / 1024)), null, \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
						}

						$this->redirect("new", "Application", null, array(
							"posting" => $posting,
							"fileError" => $upload
						));

						return;
					}
				}
			}
			else
			{
				$amountOfFiles = count($_FILES['tx_jobapplications_applicationform']['name']['upload']);
				for ($i = 0; $i < $amountOfFiles; $i++)
				{
					$error_bit = false;

					if (!in_array($_FILES['tx_jobapplications_applicationform']['type']['upload'][$i], $allowedFileTypes))
					{
						$this->addFlashMessage(LocalizationUtility::translate('fe.error.fileType', 'jobapplications'), null, \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
						$error_bit = true;
					}

					if ((int)$_FILES['tx_jobapplications_applicationform']['error']['upload'][$i] != 0)
					{
						$this->addFlashMessage(LocalizationUtility::translate('fe.error.fileSize', 'jobapplications', array("0" => intval($this->fileSizeLimit) / 1024)), null, \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
						$error_bit = true;
					}

					if ($error_bit === true)
					{
						$this->redirect("new", "Application", null, array(
							"posting" => $posting,
							"fileError" => 'files'
						));

						return;
					}
				}
			}

			// Verify length in message field;
			// front end check is already covered, this should only block requests avoiding the frontend
			if (strlen($newApplication->getMessage()) > intval($this->settings['messageMaxLength']))
			{
				$this->redirect("new", "Application", null, ["posting" => $posting]);
			}

			$newApplication->setPosting($posting);

			/* @var \ITX\Jobapplications\Domain\Model\Status $firstStatus */
			$firstStatus = $this->statusRepository->findNewStatus();

			if ($firstStatus instanceof Status)
			{
				$newApplication->setStatus($firstStatus);
			}

			// SignalSlotDispatcher BeforePostingAssign
			$signalArguments = ["application" => $newApplication];
			$signalArguments = $this->signalSlotDispatcher->dispatch(__CLASS__, "BeforeApplicationAdd", $signalArguments);

			if ($signalArguments["application"] instanceof Application)
			{
				$newApplication = $signalArguments['application'];
			}

			$this->applicationRepository->add($newApplication);
			$this->persistenceManager->persistAll();

			// Temporary posting object for unsolicited application
			if (!($posting instanceof Posting))
			{
				$posting = GeneralUtility::makeInstance(\ITX\Jobapplications\Domain\Model\Posting::class);
				$posting->setTitle(LocalizationUtility::translate("fe.application.unsolicited.title", "jobapplications"));

				$newApplication->setPosting($posting);
			}

			if ($amountOfFiles === 0)
			{
				$files = [];
				$fields = [];
				$fieldNames = [];

				$movedNewFileCover = null;
				$movedNewFileCv = null;
				$movedNewFileOther = null;
				$movedNewFileTestimonial = null;

				// Processing files
				if ($_FILES['tx_jobapplications_applicationform']['name']['cv'])
				{
					$movedNewFileCv = $this->handleFileUpload("cv", $newApplication);
					$files[] = $movedNewFileCv->getUid();
					$fieldNames[] = 'cv';
					$fields['cv'] = 1;
				}

				if ($_FILES['tx_jobapplications_applicationform']['name']['cover_letter'])
				{
					$movedNewFileCover = $this->handleFileUpload("cover_letter", $newApplication);
					$files[] = $movedNewFileCover->getUid();
					$fieldNames[] = 'cover_letter';
					$fields['cover_letter'] = 1;
				}

				if ($_FILES['tx_jobapplications_applicationform']['name']['testimonials'])
				{
					$movedNewFileTestimonial = $this->handleFileUpload("testimonials", $newApplication);
					$files[] = $movedNewFileTestimonial->getUid();
					$fieldNames[] = 'testimonials';
					$fields['testimonials'] = 1;
				}

				if ($_FILES['tx_jobapplications_applicationform']['name']['other_files'])
				{
					$movedNewFileOther = $this->handleFileUpload("other_files", $newApplication);
					$files[] = $movedNewFileOther->getUid();
					$fieldNames[] = 'other_files';
					$fields['other_files'] = 1;
				}

				if (count($files) > 0)
				{
					$this->buildRelationsLegacy($newApplication->getUid(), $files, $fields, $fieldNames, 'tx_jobapplications_domain_model_application', $newApplication->getPid());
				}
			}
			else
			{
				for ($i = 0; $i < $amountOfFiles; $i++)
				{
					$movedNewFile = $this->handleFileUploadMutliple($i, $newApplication);
					$this->buildRelations($newApplication->getUid(), $movedNewFile->getUid(), $newApplication->getPid(), $i, $amountOfFiles);
				}
			}

			// Mail Handling

			/** @var Posting $currentPosting */
			$currentPosting = $newApplication->getPosting();

			// Default contact is not available
			$contact = GeneralUtility::makeInstance(\ITX\Jobapplications\Domain\Model\Contact::class);

			$contact->setEmail($this->settings["defaultContactMailAddress"]);
			$contact->setFirstName($this->settings["defaultContactFirstName"]);
			$contact->setLastName($this->settings["defaultContactLastName"]);

			$contact = ($currentPosting->getContact() ? $currentPosting->getContact() : $contact);

			// Get and translate labels
			$salutation = LocalizationUtility::translate("fe.application.selector.".$newApplication->getSalutation(), "jobapplications");
			$salary = $newApplication->getSalaryExpectation() ? LocalizationUtility::translate("tx_jobapplications_domain_model_application.salary_expectation", "jobapplications").": ".$newApplication->getSalaryExpectation()."<br>" : "";
			$dateOfJoining = $newApplication->getEarliestDateOfJoining() ?
				LocalizationUtility::translate("tx_jobapplications_domain_model_application.earliest_date_of_joining", "jobapplications")
				.": ".$newApplication->getEarliestDateOfJoining()->format(LocalizationUtility::translate("date_format", "jobapplications"))."<br>" : "";
			$nameLabel = LocalizationUtility::translate("tx_jobapplications_domain_model_location.name", "jobapplications").": ";
			$emailLabel = LocalizationUtility::translate("tx_jobapplications_domain_model_application.email", "jobapplications").": ";
			$phoneLabel = LocalizationUtility::translate("tx_jobapplications_domain_model_application.phone", "jobapplications").": ";
			$addressLabel = LocalizationUtility::translate("tx_jobapplications_domain_model_location.address", "jobapplications").": ";
			$additionalAddress = $newApplication->getAddressAddition() ? $newApplication->getAddressAddition().'<br>' : "";
			$messageLabel = LocalizationUtility::translate("tx_jobapplications_domain_model_application.message", "jobapplications").": ";
			$message = $newApplication->getMessage() ? '<br><br>'.$messageLabel.'<br>'.$newApplication->getMessage() : "";

			// Send mail to Contact E-Mail or/and internal E-Mail
			if ($this->settings["sendEmailToContact"] == "1" || $this->settings['sendEmailToInternal'] == "1")
			{
				$mail = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\MailMessage::class);
				// Prepare and send the message
				$mail
					->setSubject(LocalizationUtility::translate("fe.email.toContactSubject", 'jobapplications', array(0 => $currentPosting->getTitle())))
					->setFrom(array($this->settings["emailSender"] => $this->settings["emailSenderName"]))
					->setReplyTo(array($newApplication->getEmail() => $newApplication->getFirstName()." ".$newApplication->getLastName()))
					->setBody('<p>'.
							  $nameLabel.$salutation.' '.$newApplication->getFirstName().' '.$newApplication->getLastName().'<br>'.
							  $emailLabel.$newApplication->getEmail().'<br>'.
							  $phoneLabel.$newApplication->getPhone().'<br>'.
							  $salary.
							  $dateOfJoining.'<br>'.
							  $addressLabel.'<br>'.$newApplication->getAddressStreetAndNumber().'<br>'
							  .$additionalAddress.
							  $newApplication->getAddressPostCode().' '.$newApplication->getAddressCity()
							  .'<br>'.$newApplication->getAddressCountry()
							  .$message.'</p>');

				// Attach all found legacy files
				$files = array($movedNewFileCv, $movedNewFileCover, $movedNewFileTestimonial, $movedNewFileOther);
				foreach ($files as $file)
				{
					if ($file instanceof FileInterface)
					{
						$mail->attach(\Swift_Attachment::fromPath($file->getPublicUrl()));
					}
				}

				$files = $newApplication->getFiles();
				foreach ($files as $file)
				{
					if ($file instanceof FileInterface)
					{
						$mail->attach(\Swift_Attachment::fromPath($file->getPublicUrl()));
					}
				}

				//Figure out who the email will be sent to and how
				if ($this->settings['sendEmailToInternal'] != "" && $this->settings['sendEmailToContact'] == "1")
				{
					$mail->setTo(array($contact->getEmail() => $contact->getFirstName()." ".$contact->getLastName()));
					$mail->setBcc($this->settings['sendEmailToInternal']);
				}
				elseif ($this->settings['sendEmailToContact'] != "1" && $this->settings['sendEmailToInternal'] != "")
				{
					$mail->setTo(array($this->settings['sendEmailToInternal'] => 'Internal'));
				}
				elseif ($this->settings['sendEmailToContact'] == "1" && $this->settings['sendEmailToInternal'] != "1")
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

			// Now send a mail to the applicant
			if ($this->settings["sendEmailToApplicant"] == "1")
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
						$salutation = LocalizationUtility::translate("fe.application.selector.mr", "jobapplications");
						break;
					case 2:
						$salutation = LocalizationUtility::translate("fe.application.selector.mrs", "jobapplications");
						break;
				}
				$body = str_replace("%applicantSalutation%", $salutation, $body);
				$body = str_replace("%applicantFirstname%", $newApplication->getFirstName(), $body);
				$body = str_replace("%applicantLastname%", $newApplication->getLastName(), $body);
				$body = str_replace("%postingTitle%", $currentPosting->getTitle(), $body);

				$mail
					->setSubject($subject)
					->setFrom(array($this->settings["emailSender"] => $this->settings["emailSenderName"]))
					->setTo(array($newApplication->getEmail() => $newApplication->getFirstName()." ".$newApplication->getLastName()))
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

			// If applications should not be saved delete them here
			if ($this->settings['saveApplicationInBackend'] != "1")
			{
				$this->applicationRepository->remove($newApplication);
				$this->applicationFileService->deleteApplicationFolder($this->applicationFileService->getApplicantFolder($newApplication));
				$this->persistenceManager->persistAll();
			}

			$this->redirect("success", null, null, [
				"firstName" => $newApplication->getFirstName(),
				"lastName" => $newApplication->getLastName(),
				"salutation" => $newApplication->getSalutation(),
				"postingUid" => $currentPosting->getUid()
			], $this->settings["successPage"]);
		}

		/**
		 * Attaches existing File to Domain Model Record
		 *
		 * @param $newStorageUid ;UID of Record or Domain Model the file will attach to
		 * @param $files         ;from Objectmanagers storage repository
		 * @param $fields
		 * @param $fieldNames    ;fieldnames
		 * @param $table         ;table tca domain table name e.g. tx_<extensionName>_domain_model_<domainModelName>
		 * @param $newStoragePid ;PID of Record or Domain Model the file will attach to
		 */
		private function buildRelationsLegacy($newStorageUid, array $files, array $fields, array $fieldNames, $table, $newStoragePid)
		{
			$database = GeneralUtility::makeInstance(ConnectionPool::class);
			for ($i = 0; $i < count($files); $i++)
			{
				$database
					->getConnectionForTable('sys_file_reference')
					->insert(
						'sys_file_reference',
						[
							'tstamp' => time(),
							'crdate' => time(),
							'cruser_id' => 1,
							'uid_local' => $files[$i],
							'uid_foreign' => $newStorageUid,
							'tablenames' => $table,
							'fieldname' => $fieldNames[$i],
							'pid' => $newStoragePid,
							'table_local' => 'sys_file',
							'sorting_foreign' => 1
						]
					);
			}

			$database
				->getConnectionForTable('tx_jobapplications_domain_model_application')
				->update(
					"tx_jobapplications_domain_model_application",
					$fields, [
						'uid' => $newStorageUid
					]);
		}

		/**
		 * @param int $objectUid  The Uid of the domain object
		 * @param int $fileUid    The Uid of the actual file
		 * @param int $objectPid  The pid of the domain object
		 * @param int $iteration  The current iteration
		 * @param int $totalFiles The total amount of iterations
		 */
		private function buildRelations(int $objectUid, int $fileUid, int $objectPid, $iteration = 0, $totalFiles = 1)
		{
			/** @var ConnectionPool $database */
			$database = GeneralUtility::makeInstance(ConnectionPool::class);

			$database
				->getConnectionForTable('sys_file_reference')
				->insert(
					'sys_file_reference',
					[
						'tstamp' => time(),
						'crdate' => time(),
						'cruser_id' => 1,
						'uid_local' => $fileUid,
						'uid_foreign' => $objectUid,
						'tablenames' => "tx_jobapplications_domain_model_application",
						'fieldname' => "files",
						'pid' => $objectPid,
						'table_local' => 'sys_file',
						'sorting_foreign' => $iteration + 1
					]
				);

			if ($totalFiles - 1 === $iteration)
			{
				$database
					->getConnectionForTable('tx_jobapplications_domain_model_application')
					->update(
						"tx_jobapplications_domain_model_application",
						["files" => $totalFiles], [
							'uid' => $newStorageUid
						]);
			}
		}

		/**
		 * @param string                                        $fieldName
		 * @param \ITX\Jobapplications\Domain\Model\Application $domainObject
		 *
		 * @return \TYPO3\CMS\Core\Resource\FileInterface
		 * @throws \TYPO3\CMS\Core\Resource\Exception\ExistingTargetFileNameException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\ExistingTargetFolderException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderWritePermissionsException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException
		 */
		private function handleFileUpload(string $fieldName, \ITX\Jobapplications\Domain\Model\Application $domainObject)
		{

			$folder = $this->applicationFileService->getApplicantFolder($domainObject);

			$tmpFile = $_FILES['tx_jobapplications_applicationform']['tmp_name'][$fieldName];

			/* @var \TYPO3\CMS\Core\Resource\StorageRepository $storageRepository */
			$storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
			$storage = $storageRepository->findByUid('1');

			//build the new storage folder
			if ($storage->hasFolder($folder))
			{
				$targetFolder = $storage->getFolder($folder);
			}
			else
			{
				$targetFolder = $storage->createFolder($folder);
			}

			//file name
			$newFileName = $fieldName."_".$domainObject->getLastName()."_".$domainObject->getFirstName();
			if (strlen($newFileName) > 255)
			{
				$newFileName = substr($newFileName, 0, 245);
			}

			$newFileName .= "_id_".$domainObject->getPosting()->getUid().".pdf";

			//build sys_file
			$movedNewFile = $storage->addFile($tmpFile, $targetFolder, $newFileName);
			$this->persistenceManager->persistAll();

			return $movedNewFile;
		}

		/**
		 * @param string                                        $fieldName
		 * @param \ITX\Jobapplications\Domain\Model\Application $domainObject
		 *
		 * @return FileInterface
		 * @throws \TYPO3\CMS\Core\Resource\Exception\ExistingTargetFolderException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderWritePermissionsException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException
		 *
		 * @throws \TYPO3\CMS\Core\Resource\Exception\ExistingTargetFileNameException
		 */
		private function handleFileUploadMutliple(int $position, \ITX\Jobapplications\Domain\Model\Application $domainObject)
		{

			$folder = $this->applicationFileService->getApplicantFolder($domainObject);

			$tmpFile = $_FILES['tx_jobapplications_applicationform']['tmp_name']['upload'][$position];
			$oGfileName = $_FILES['tx_jobapplications_applicationform']['name']['upload'][$position];

			/* @var \TYPO3\CMS\Core\Resource\StorageRepository $storageRepository */
			$storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
			$storage = $storageRepository->findByUid('1');

			//build the new storage folder
			if ($storage->hasFolder($folder))
			{
				$targetFolder = $storage->getFolder($folder);
			}
			else
			{
				$targetFolder = $storage->createFolder($folder);
			}

			//file name
			$newFileName = (new \TYPO3\CMS\Core\Resource\Driver\LocalDriver)->sanitizeFileName($oGfileName);

			//build sys_file
			$movedNewFile = $storage->addFile($tmpFile, $targetFolder, $newFileName);
			$this->persistenceManager->persistAll();

			return $movedNewFile;
		}
	}