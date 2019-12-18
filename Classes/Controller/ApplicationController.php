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
	use TYPO3\CMS\Core\Database\ConnectionPool;
	use TYPO3\CMS\Core\Messaging\FlashMessage;
	use TYPO3\CMS\Core\Resource\ResourceFactory;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Extbase\Domain\Model\FileReference;
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
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		protected $applicationRepository = null;

		protected $fileSizeLimit;

		/**
		 * @var \ITX\Jobs\Domain\Repository\PostingRepository
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		private $postingRepository;

		/**
		 * @var \ITX\Jobs\Domain\Repository\StatusRepository
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		private $statusRepository;

		/**
		 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		protected $persistenceManager;

		/**
		 * @var \ITX\Jobs\Service\ApplicationFileService
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
		 * * @param ITX\Jobs\Domain\Model\Posting
		 *
		 * @return void
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
		 */
		public function newAction()
		{

			if ($this->request->hasArgument("posting"))
			{
				$posting = $this->request->getArgument("posting");
			}
			elseif ($_REQUEST['postingApp'])
			{
				$posting = $_REQUEST['postingApp'];
			}

			$this->fileSizeLimit = GeneralUtility::getMaxUploadFileSize();

			if ($this->request->hasArgument("fileError"))
			{
				$error = $this->request->getArgument("fileError");
				$this->view->assign("fileError", $error);
			}
			else
			{
				$this->view->assign("fileError", 0);
			}

			if ($posting)
			{
				$postingObject = $this->postingRepository->findByUid($posting);

				$titleProvider = GeneralUtility::makeInstance(JobsPageTitleProvider::class);

				$title = $this->settings["pageTitle"];
				if ($title != "")
				{
					$title = str_replace("%postingTitle%", $postingObject->getTitle(), $title);
				}
				else
				{
					$title = $postingObject->getTitle();
				}

				$titleProvider->setTitle($title);

				$this->view->assign('posting', $postingObject);
			}

			$this->view->assign("fileSizeLimit", strval($this->fileSizeLimit) / 1024);
		}

		public function successAction()
		{
			$lastName = $this->request->hasArgument("lastName") ? $this->request->getArgument("lastName") : "";
			$firstName = $this->request->hasArgument("firstName") ? $this->request->getArgument("firstName") : "";
			$salutation = $this->request->hasArgument("salutation") ? $this->request->getArgument("salutation") : "";

			if($salutation == "div")
			{
				$salutation = $firstName;
			} else {
				$salutation = LocalizationUtility::translate("fe.application.selector.".$salutation, "jobs");
			}

			$text = $this->settings["successMessage"];
			$text = str_replace("%lastName%", $lastName, $text);
			$text = str_replace("%firstName%", $firstName, $text);
			$text = str_replace("%salutation%", $salutation, $text);

			$this->view->assign("message", $text);
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
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
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

			//Verify length in message field
			if (strlen($newApplication->getMessage()) > intval($this->settings['messageMaxLength']))
			{
				$this->redirect("new", "Application", null, array());
			}

			$newApplication->setPosting($posting);

			/* @var \ITX\Jobs\Domain\Model\Status $firstStatus */
			$firstStatus = $this->statusRepository->findByUid(1);
			if($firstStatus)
			{
				$newApplication->setStatus($firstStatus);
			}

			// SignalSlotDispatcher BeforePostingAssign
			$signalArguments = ["application" => $newApplication];
			$signalArguments = $this->signalSlotDispatcher->dispatch(__CLASS__, "BeforeApplicationAdd", $signalArguments);

			if ($signalArguments["application"])
			{
				$newApplication = $signalArguments['application'];
			}

			$this->applicationRepository->add($newApplication);
			$this->persistenceManager->persistAll();

			/* @var \ITX\Jobs\Domain\Model\Application $newApplication */
			$newApplication = $this->applicationRepository->findByUid($newApplication->getUid());

			$files = [];
			$fields = [];
			$fieldNames = [];

			// Processing files
			if ($_FILES['tx_jobs_applicationform']['name']['cv'])
			{
				$movedNewFileCv = $this->handleFileUpload("cv", $newApplication);
				$files[] = $movedNewFileCv->getUid();
				$fieldNames[] = 'cv';
				$fields['cv'] = 1;
			}

			if ($_FILES['tx_jobs_applicationform']['name']['cover_letter'])
			{
				$movedNewFileCover = $this->handleFileUpload("cover_letter", $newApplication);
				$files[] = $movedNewFileCover->getUid();
				$fieldNames[] = 'cover_letter';
				$fields['cover_letter'] = 1;
			}

			if ($_FILES['tx_jobs_applicationform']['name']['testimonials'])
			{
				$movedNewFileTestimonial = $this->handleFileUpload("testimonials", $newApplication);
				$files[] = $movedNewFileTestimonial->getUid();
				$fieldNames[] = 'testimonials';
				$fields['testimonials'] = 1;
			}

			if ($_FILES['tx_jobs_applicationform']['name']['other_files'])
			{
				$movedNewFileOther = $this->handleFileUpload("other_files", $newApplication);
				$files[] = $movedNewFileOther->getUid();
				$fieldNames[] = 'other_files';
				$fields['other_files'] = 1;
			}

			if (count($files) > 0)
			{
				$this->buildRelations($newApplication->getUid(), $files, $fields, $fieldNames, 'tx_jobs_domain_model_application', $newApplication->getPid());
			}

			// Mail Handling

			$currentPosting = $this->postingRepository->findByUid($newApplication->getPosting());
			$contact = $currentPosting->getContact();

			// Get and translate labels
			$salutation = LocalizationUtility::translate("fe.application.selector.".$newApplication->getSalutation(), "jobs");
			$salary = $newApplication->getSalaryExpectation() ? LocalizationUtility::translate("tx_jobs_domain_model_application.salary_expectation", "jobs").": ".$newApplication->getSalaryExpectation()."<br>" : "";
			$dateOfJoining = $newApplication->getEarliestDateOfJoining() ?
				LocalizationUtility::translate("tx_jobs_domain_model_application.earliest_date_of_joining", "jobs")
				.": ".$newApplication->getEarliestDateOfJoining()->format(LocalizationUtility::translate("date_format", "jobs"))."<br>" : "";
			$nameLabel = LocalizationUtility::translate("tx_jobs_domain_model_location.name", "jobs").": ";
			$emailLabel = LocalizationUtility::translate("tx_jobs_domain_model_application.email", "jobs").": ";
			$phoneLabel = LocalizationUtility::translate("tx_jobs_domain_model_application.phone", "jobs").": ";
			$addressLabel = LocalizationUtility::translate("tx_jobs_domain_model_location.address", "jobs").": ";
			$additionalAddress = $newApplication->getAddressAddition() ? $newApplication->getAddressAddition().'<br>' : "";
			$messageLabel = LocalizationUtility::translate("tx_jobs_domain_model_application.message", "jobs").": ";
			$message = $newApplication->getMessage() ? '<br><br>'.$messageLabel.'<br>'.$newApplication->getMessage() : "";

			// Send mail to Contact E-Mail or/and internal E-Mail
			if ($this->settings["sendEmailToContact"] || $this->settings['sendEmailToInternal'])
			{
				$mail = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\MailMessage::class);
				// Prepare and send the message
				$mail
					->setSubject(LocalizationUtility::translate("fe.email.toContactSubject", 'jobs', array(0 => $currentPosting->getTitle())))
					->setFrom(array($newApplication->getEmail() => $newApplication->getFirstName()." ".$newApplication->getLastName()))
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

				// Attach all found files
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

			// Now send a mail to the applicant
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
			if (!$this->settings['saveApplicationInBackend'])
			{
				$this->persistenceManager->remove($newApplication);
				$this->applicationFileService->deleteApplicationFolder($this->applicationFileService->getApplicantFolder($newApplication));
				$this->persistenceManager->persistAll();
			}

			$this->redirect("success",null,null, [
				"firstName" => $newApplication->getFirstName(),
				"lastName" => $newApplication->getLastName(),
				"salutation" => $newApplication->getSalutation()
			], $this->settings["successPage"]);
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
		private function buildRelations($newStorageUid, array $files, array $fields, array $fieldNames, $table, $newStoragePid)
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
				->getConnectionForTable('tx_jobs_domain_model_application')
				->update(
					"tx_jobs_domain_model_application",
					$fields, [
						'uid' => $newStorageUid
					]);
		}

		/**
		 * @param $fieldName
		 * @param $domainObject \ITX\Jobs\Domain\Model\Application
		 *
		 * @return mixed
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException
		 */
		private function handleFileUpload($fieldName, \ITX\Jobs\Domain\Model\Application $domainObject)
		{

			$folder = $this->applicationFileService->getApplicantFolder($domainObject);

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
	}