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
	use ITX\Jobapplications\Utility\Mail\MailInterface;
	use ITX\Jobapplications\Utility\Typo3VersionUtility;
	use ITX\Jobapplications\Utility\UploadFileUtility;
	use Psr\Log\LogLevel;
	use Symfony\Component\Mime\Address;
	use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
	use TYPO3\CMS\Core\Database\ConnectionPool;
	use TYPO3\CMS\Core\Localization\Exception\FileNotFoundException;
	use TYPO3\CMS\Core\Mail\FluidEmail;
	use TYPO3\CMS\Core\Mail\Mailer;
	use TYPO3\CMS\Core\Messaging\FlashMessage;
	use TYPO3\CMS\Core\Resource\File;
	use TYPO3\CMS\Core\Resource\FileInterface;
	use TYPO3\CMS\Core\Resource\ResourceStorage;
	use TYPO3\CMS\Core\Resource\StorageRepository;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
	use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;
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

		/** @var int $fileSizeLimit */
		protected $fileSizeLimit;

		/** @var string $allowedFileTypesString */
		protected $allowedFileTypesString;
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
		protected $logger;
		/** @var int Major TYPO3 Version number */
		protected $version;
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
		 * initialize create action
		 * adjusts date time format to y-m-d
		 *
		 *
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
		 */
		public function initializeCreateAction(): void
		{
			$this->arguments->getArgument('newApplication')
							->getPropertyMappingConfiguration()->forProperty('earliestDateOfJoining')
							->setTypeConverterOption(
								DateTimeConverter::class,
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
		 * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
		 * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
		 */
		public function initializeAction(): void
		{
			/** @var ExtensionConfiguration $extconf */
			$extconf = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class);
			$extConfLimit = $extconf->get('jobapplications', 'customFileSizeLimit');
			$this->allowedFileTypesString = $extconf->get('jobapplications', 'allowedFileTypes');

			if ($extConfLimit !== '')
			{
				$this->fileSizeLimit = (int)$extConfLimit > (int)GeneralUtility::getMaxUploadFileSize() ?
					GeneralUtility::getMaxUploadFileSize() : (int)$extConfLimit;
			}
			else
			{
				$this->fileSizeLimit = (int)GeneralUtility::getMaxUploadFileSize();
			}

			$this->version = Typo3VersionUtility::getMajorVersion();
		}

		/**
		 * @param Posting|null $posting
		 *
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
		 */
		public function newAction(Posting $posting = null): void
		{
			/*
			Getting posting when Detailview and applicationform are on the same page.
			Limited to posting via GET Variable which isn't the best way of.
			Might need to find a better solution in the future
			*/
			if ($posting === null && $_REQUEST['postingApp'])
			{
				$postingUid = (int)$_REQUEST['postingApp'];
				/** @var Posting $posting */
				$posting = $this->postingRepository->findByUid($postingUid);
			}

			if ($posting instanceof Posting)
			{
				/** @var JobsPageTitleProvider $titleProvider */
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

			$this->view->assign("fileSizeLimit", (string)$this->fileSizeLimit / 1024);

			$this->view->assign("allowedFileTypes", $this->allowedFileTypesString);

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
		 * @param string $firstName
		 * @param string $lastName
		 * @param string $salutation
		 * @param array  $problems
		 * @param int    $postingUid
		 */
		public function successAction($firstName, $lastName, $salutation, $problems, $postingUid = -1)
		{
			$salutationValue = $salutation;

			if ($salutation === 'div' || $salutation === '')
			{
				$salutation = $firstName;
			}
			else
			{
				$salutation = LocalizationUtility::translate('fe.application.selector.'.$salutation, 'jobapplications');
			}

			/** @var Posting|null $posting */
			$posting = null;

			if ($postingUid !== -1)
			{
				$posting = $this->postingRepository->findByUid($postingUid);
				$this->view->assign('posting', $posting);
			}

			$this->view->assign('firstName', $firstName);
			$this->view->assign('lastName', $lastName);
			$this->view->assign('salutation', $salutation);
			$this->view->assign('problems', $problems);
			$posting ? $this->view->assign('salutationValue', $salutationValue) : false;
		}

		/**
		 * @param Application  $newApplication
		 * @param Posting|null $posting
		 *
		 * @throws \TYPO3\CMS\Core\Resource\Exception\ExistingTargetFileNameException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\ExistingTargetFolderException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderWritePermissionsException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
		 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
		 * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
		 * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
		 * @throws \TYPO3\CMS\Form\Domain\Exception\IdentifierNotValidException
		 */
		public function createAction(\ITX\Jobapplications\Domain\Model\Application $newApplication, \ITX\Jobapplications\Domain\Model\Posting $posting = null): void
		{
			$problemWithApplicantMail = false;
			$problemWithNotificationMail = false;
			$savedInBackend = true;

			$arguments = $this->request->getArguments();

			$isMultiFile = false;
			$multiUploadFiles = [];
			$legacyUploadfiles = [];

			// Check which kind of uploads were sent
			if (!empty($arguments['files']))
			{
				$isMultiFile = true;
			}
			else
			{
				// Normalize file array -> free choice whether multi or single upload
				$fileIndices = ['cv', 'cover_letter', 'testimonials', 'other_files'];

				foreach ($fileIndices as $fileIndex)
				{
					if (is_string($arguments[$fileIndex]) && !empty($arguments[$fileIndex]))
					{
						$arguments[$fileIndex] = [$arguments[$fileIndex]];
						continue;
					}

					if (is_array($arguments[$fileIndex]))
					{
						foreach ($arguments[$fileIndex] as $index => $fileId)
						{
							if (!is_string($fileId) || empty($fileId))
							{
								array_splice($arguments[$fileIndex], $index, 1);
							}
						}
						continue;
					}

					$arguments[$fileIndex] = [];
				}
			}

			// Verify length in message field;
			// front end check is already covered, this should only block requests avoiding the frontend
			if (strlen($newApplication->getMessage()) > (int)$this->settings['messageMaxLength'])
			{
				$this->addFlashMessage("Message too long", "Rejected", FlashMessage::ERROR);
				$this->redirect("new", "Application", null, ["posting" => $posting]);
			}

			if ($posting instanceof Posting)
			{
				$newApplication->setPosting($posting);
			}

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
				/** @var Posting $posting */
				$posting = GeneralUtility::makeInstance(\ITX\Jobapplications\Domain\Model\Posting::class);
				$posting->setTitle(LocalizationUtility::translate("fe.application.unsolicited.title", "jobapplications"));

				$newApplication->setPosting($posting);
			}

			if (!$isMultiFile)
			{
				// Process files
				$legacyUploadfiles[] = $this->processFiles($newApplication, $arguments['cv'], 'cv', 'cv_');
				$legacyUploadfiles[] = $this->processFiles($newApplication, $arguments['cover_letter'], 'cover_letter', 'cover_letter_');
				$legacyUploadfiles[] = $this->processFiles($newApplication, $arguments['testimonials'], 'testimonials', 'testimonials_');
				$legacyUploadfiles[] = $this->processFiles($newApplication, $arguments['other_files'], 'other_files', 'other_files_');
			}
			else
			{
				$multiUploadFiles = $this->processFiles($newApplication, $arguments['files'], 'files');
			}

			// Mail Handling

			/** @var Posting $currentPosting */
			$currentPosting = $newApplication->getPosting();

			// Default contact is not available
			$contact = GeneralUtility::makeInstance(\ITX\Jobapplications\Domain\Model\Contact::class);

			$contact->setEmail($this->settings["defaultContactMailAddress"]);
			$contact->setFirstName($this->settings["defaultContactFirstName"]);
			$contact->setLastName($this->settings["defaultContactLastName"]);

			$contact = ($currentPosting->getContact() ?: $contact);

			/** @var Mailer $mailer */
			$mailer = GeneralUtility::makeInstance(Mailer::class);

			// Send mail to Contact E-Mail or/and internal E-Mail
			if ($this->settings["sendEmailToContact"] === "1" || $this->settings['sendEmailToInternal'] !== "")
			{
				$mail = GeneralUtility::makeInstance(FluidEmail::class);
				$mail->setTemplate('JobsNotificationMail');

				$mail->format($this->settings['emailContentType']);

				// Prepare and send the message
				$mail
					->subject(LocalizationUtility::translate("fe.email.toContactSubject", 'jobapplications', [0 => $currentPosting->getTitle()]))
					->from(new Address($this->settings["emailSender"], $this->settings["emailSenderName"]))
					->replyTo(new Address($newApplication->getEmail(), $newApplication->getFirstName()." ".$newApplication->getLastName()))
					->assignMultiple(['application' => $newApplication, 'settings' => $this->settings, 'currentPosting' => $currentPosting]);

				foreach ($legacyUploadfiles as $fileArray)
				{
					foreach ($fileArray as $file)
					{
						if ($file instanceof FileInterface)
						{
							$mail->attach($file->getPublicUrl());
						}
					}
				}

				foreach ($multiUploadFiles as $file)
				{
					if ($file instanceof FileInterface)
					{
						$mail->attach($file->getPublicUrl());
					}
				}

				//Figure out who the email will be sent to and how
				if ($this->settings['sendEmailToInternal'] != "" && $this->settings['sendEmailToContact'] == "1")
				{
					$mail->to(new Address($contact->getEmail(), $contact->getFirstName().' '.$contact->getLastName()));
					$mail->bcc(new Address($this->settings['sendEmailToInternal']));
				}
				else if ($this->settings['sendEmailToContact'] != "1" && $this->settings['sendEmailToInternal'] != "")
				{
					$mail->to(new Address($this->settings['sendEmailToInternal'], 'Internal'));
				}
				else if ($this->settings['sendEmailToContact'] == "1" && $this->settings['sendEmailToInternal'] != "1")
				{
					$mail->to(new Address($contact->getEmail(), $contact->getFirstName()." ".$contact->getLastName()));
				}

				try
				{
					$mailer->send($mail);
				}
				catch (\Exception $e)
				{
					$this->logger->log(LogLevel::CRITICAL, 'Error trying to send a mail: '.$e->getMessage(), [$this->settings, $mail]);
					$problemWithNotificationMail = true;
				}
			}

			// Now send a mail to the applicant
			if ($this->settings["sendEmailToApplicant"] === "1")
			{
				$mail = GeneralUtility::makeInstance(FluidEmail::class);
				$mail->setTemplate('JobsApplicantMail');

				$mail->format($this->settings['emailContentType']);

				//Template Messages
				$subject = $this->settings['sendEmailToApplicantSubject'];
				$subject = str_replace("%postingTitle%", $currentPosting->getTitle(), $subject);

				$mail
					->subject($subject)
					->from(new Address($this->settings["emailSender"], $this->settings["emailSenderName"]))
					->to(new Address($newApplication->getEmail(), $newApplication->getFirstName()." ".$newApplication->getLastName()))
					->assignMultiple(['application' => $newApplication, 'settings' => $this->settings]);

				try
				{
					$mailer->send($mail);
				}
				catch (\Exception $e)
				{
					$this->logger->log(\TYPO3\CMS\Core\Log\LogLevel::CRITICAL, 'Error trying to send a mail: '.$e->getMessage(), [$this->settings, $mail]);
					$problemWithApplicantMail = true;
				}
			}

			// If applications should not be saved delete them here
			if ($this->settings['saveApplicationInBackend'] != "1")
			{
				$savedInBackend = false;
				$this->applicationRepository->remove($newApplication);
				$this->applicationFileService->deleteApplicationFolder($this->applicationFileService->getApplicantFolder($newApplication));
				$this->persistenceManager->persistAll();
			}

			// Build uri and redirect to success page
			$this->uriBuilder->reset()->setCreateAbsoluteUri(true);

			$this->uriBuilder->setTargetPageUid((int)$this->settings['successPage']);

			if (\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SSL'))
			{
				$this->uriBuilder->setAbsoluteUriScheme('https');
			}

			$problems = [
				'problemWithApplicantMail' => $problemWithApplicantMail,
				'problemWithNotificationMail' => $problemWithNotificationMail,
				'savedInBackend' => $savedInBackend
			];

			$uri = $this->uriBuilder->uriFor('success',
				[
					'firstName' => $newApplication->getFirstName(),
					'lastName' => $newApplication->getLastName(),
					'salutation' => $newApplication->getSalutation(),
					'problems' => $problems,
					'postingUid' => $currentPosting->getUid() ?: -1
				], 'Application', null, 'SuccessPage');

			$this->redirectToUri($uri);
		}

		/**
		 * @param Application $newApplication
		 * @param array       $fileIds
		 * @param string      $fieldName
		 * @param string      $fileNamePrefix
		 *
		 * @return array
		 * @throws \TYPO3\CMS\Core\Resource\Exception\ExistingTargetFileNameException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\ExistingTargetFolderException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderWritePermissionsException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException
		 */
		private function processFiles(Application $newApplication, array $fileIds, string $fieldName, string $fileNamePrefix = ''): array
		{
			$uploadUtility = new UploadFileUtility();

			$i = 0;
			$return_files = [];
			foreach ($fileIds as $fileId)
			{
				if (empty($fileId))
				{
					break;
				}

				$movedNewFile = $this->handleFileUpload(
					$uploadUtility->getFilePath($fileId), $uploadUtility->getFileName($fileId),
					$newApplication, $fileNamePrefix);
				$return_files[] = $movedNewFile;
				$uploadUtility->deleteFolder($fileId);

				$this->buildRelations($newApplication->getUid(), $movedNewFile->getUid(), $newApplication->getPid(), $fieldName, $i, count($fileIds));
				$i++;
			}

			return $return_files;
		}

		/**
		 * @param string                                        $filePath
		 * @param string                                        $fileName
		 * @param \ITX\Jobapplications\Domain\Model\Application $domainObject
		 * @param string                                        $prefix
		 *
		 * @return File|FileInterface
		 * @throws \TYPO3\CMS\Core\Resource\Exception\ExistingTargetFileNameException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\ExistingTargetFolderException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderWritePermissionsException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException
		 */
		private function handleFileUpload(string                                        $filePath, string $fileName,
										  \ITX\Jobapplications\Domain\Model\Application $domainObject, string $prefix = '')
		{

			$folder = $this->applicationFileService->getApplicantFolder($domainObject);

			/* @var \TYPO3\CMS\Core\Resource\StorageRepository $storageRepository */
			$storageRepository = $this->objectManager->get(StorageRepository::class);
			$storage = $storageRepository->findByUid('1');

			if (!$storage instanceof ResourceStorage) {
				throw new FileNotFoundException("Could not find fileadmin with uid 1");
			}

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
			$newFileName = (new \TYPO3\CMS\Core\Resource\Driver\LocalDriver)->sanitizeFileName($prefix.$fileName);

			//build sys_file
			$movedNewFile = $storage->addFile($filePath, $targetFolder, $newFileName);
			$this->persistenceManager->persistAll();

			return $movedNewFile;
		}

		/**
		 * @param int $objectUid  The Uid of the domain object
		 * @param int $fileUid    The Uid of the actual file
		 * @param int $objectPid  The pid of the domain object
		 * @param int $iteration  The current iteration
		 * @param int $totalFiles The total amount of iterations
		 */
		private function buildRelations(int $objectUid, int $fileUid, int $objectPid, string $field, int $iteration = 0, int $totalFiles = 1): void
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
						'fieldname' => $field,
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
							'uid' => $objectUid
						]);
			}
		}
	}