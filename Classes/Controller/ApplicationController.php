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
	use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
	use TYPO3\CMS\Core\Log\LogManager;
	use Psr\Http\Message\ResponseInterface;
	use ITX\Jobapplications\Domain\Model\Contact;
	use TYPO3\CMS\Core\Resource\Driver\LocalDriver;
	use ITX\Jobapplications\Domain\Repository\ApplicationRepository;
	use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
	use ITX\Jobapplications\Service\ApplicationFileService;
	use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
	use ITX\Jobapplications\Domain\Repository\PostingRepository;
	use ITX\Jobapplications\Domain\Repository\StatusRepository;
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
	use TYPO3\CMS\Core\Resource\ResourceStorageInterface;
	use TYPO3\CMS\Core\Resource\ResourceStorage;
	use TYPO3\CMS\Core\Resource\StorageRepository;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Core\Utility\MathUtility;
	use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
	use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;
	use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

	/**
	 * ApplicationController
	 */
	class ApplicationController extends ActionController
	{
		public const UPLOAD_MODE_FILES = 'files';
		public const UPLOAD_MODE_LEGACY = 'legacy';
		public const UPLOAD_MODE_NONE = 'none';

		/**
		 * applicationRepository
		 *
		 * @var \ITX\Jobapplications\Domain\Repository\ApplicationRepository
		 */
		protected $applicationRepository = null;

		/** @var int $fileSizeLimit */
		protected $fileSizeLimit;

		/** @var string $allowedFileTypesString */
		protected $allowedFileTypesString;
		/**
		 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
		 */
		protected $persistenceManager;
		/**
		 * @var \ITX\Jobapplications\Service\ApplicationFileService
		 */
		protected $applicationFileService;
		/**
		 * signalSlotDispatcher
		 *
		 * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
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
		 */
		private $postingRepository;
		/**
		 * @var \ITX\Jobapplications\Domain\Repository\StatusRepository
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
								DateTimeConverter::CONFIGURATION_DATE_FORMAT,
								'Y-m-d'
							);

			$this->logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);
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
			$extconf = GeneralUtility::makeInstance(ExtensionConfiguration::class);
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
		public function newAction(Posting $posting = null): ResponseInterface
		{

			// Getting posting when Detailview and applicationform are on the same page.
			$parameters = GeneralUtility::_GET("tx_jobapplications_detailview");
			if ($posting === null && !empty($parameters) && !empty($parameters['posting']) && MathUtility::canBeInterpretedAsInteger($parameters['posting']))
			{
				$postingUid = (int)$parameters['posting'];
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
			return $this->htmlResponse();
		}

		/**
		 * @param string $firstName
		 * @param string $lastName
		 * @param string $salutation
		 * @param array  $problems
		 * @param int    $postingUid
		 */
		public function successAction($firstName, $lastName, $salutation, $problems, $postingUid = -1): ResponseInterface
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
			return $this->htmlResponse();
		}

		private function hasArrayWithIndicesNonEmpty(array $array, array $indices): bool
		{
			foreach ($indices as $index)
			{
				if (isset($array[$index]) && count($array[$index]) > 0)
				{
					return true;
				}
			}

			return false;
		}

		private function isStringArray(?array $array): bool
		{
			if (!is_array($array))
			{
				return false;
			}

			foreach ($array as $item)
			{
				if (is_string($item))
				{
					return true;
				}
			}

			return false;
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
		public function createAction(Application $newApplication, Posting $posting = null): void
		{
			$problemWithApplicantMail = false;
			$problemWithNotificationMail = false;
			$savedInBackend = true;

			$arguments = $this->request->getArguments();

			$uploadMode = self::UPLOAD_MODE_NONE;

			$multiUploadFiles = [];
			$legacyUploadfiles = [];
			$fileIndices = ['cv', 'cover_letter', 'testimonials', 'other_files'];

			$fileStorage = (int)($this->settings['fileStorage'] ?? 1);

			// Normalize file array -> free choice whether multi or single upload
			foreach ($fileIndices as $fileIndex)
			{
				if (isset($arguments[$fileIndex]) && is_string($arguments[$fileIndex]) && !empty($arguments[$fileIndex]))
				{
					$arguments[$fileIndex] = [$arguments[$fileIndex]];
					continue;
				}

				if (isset($arguments[$fileIndex]) && is_array($arguments[$fileIndex]))
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

			// Check which kind of uploads were sent
			if ($this->isStringArray($arguments['files'] ?? []))
			{
				$uploadMode = self::UPLOAD_MODE_FILES;
			}
			else if ($this->hasArrayWithIndicesNonEmpty($arguments, $fileIndices))
			{
				$uploadMode = self::UPLOAD_MODE_LEGACY;
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
				$posting = GeneralUtility::makeInstance(Posting::class);
				$posting->setTitle(LocalizationUtility::translate("fe.application.unsolicited.title", "jobapplications"));

				$newApplication->setPosting($posting);
			}

			// Process files
			switch ($uploadMode)
			{
				case self::UPLOAD_MODE_LEGACY:
					$legacyUploadfiles[] = $this->processFiles($newApplication, $arguments['cv'], 'cv', $fileStorage, 'cv_');
					$legacyUploadfiles[] = $this->processFiles($newApplication, $arguments['cover_letter'], $fileStorage, 'cover_letter', 'cover_letter_');
					$legacyUploadfiles[] = $this->processFiles($newApplication, $arguments['testimonials'], $fileStorage, 'testimonials', 'testimonials_');
					$legacyUploadfiles[] = $this->processFiles($newApplication, $arguments['other_files'], $fileStorage, 'other_files', 'other_files_');
					break;
				case self::UPLOAD_MODE_FILES:
					$multiUploadFiles = $this->processFiles($newApplication, $arguments['files'], 'files', $fileStorage);
					break;
				default:
					// No files were uploaded, so we don't have to process any
			}

			// Mail Handling

			/** @var Posting $currentPosting */
			$currentPosting = $newApplication->getPosting();

			// Default contact is not available
			$contact = GeneralUtility::makeInstance(Contact::class);

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
							$mail->attach($file->getForLocalProcessing(false));
						}
					}
				}

				foreach ($multiUploadFiles as $file)
				{
					if ($file instanceof FileInterface)
					{
						$mail->attach($file->getForLocalProcessing(false));
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
				$this->applicationFileService->deleteApplicationFolder($this->applicationFileService->getApplicantFolder($newApplication), $fileStorage);
				$this->persistenceManager->persistAll();
			}

			// Build uri and redirect to success page
			$this->uriBuilder->reset()->setCreateAbsoluteUri(true);

			$this->uriBuilder->setTargetPageUid((int)$this->settings['successPage']);

			if (GeneralUtility::getIndpEnv('TYPO3_SSL'))
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
		 * @param int         $fileStorage
		 * @param string      $fileNamePrefix
		 *
		 * @return array
		 * @throws \TYPO3\CMS\Core\Resource\Exception\ExistingTargetFileNameException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\ExistingTargetFolderException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderWritePermissionsException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException
		 */
		private function processFiles(Application $newApplication, array $fileIds, string $fieldName, int $fileStorage, string $fileNamePrefix = ''): array
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
					$newApplication, $fileStorage, $fileNamePrefix);
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
		 * @param int                                           $fileStorage
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
										  \ITX\Jobapplications\Domain\Model\Application $domainObject, int $fileStorage, string $prefix = ''): FileInterface
		{

			$folder = $this->applicationFileService->getApplicantFolder($domainObject);

			/* @var \TYPO3\CMS\Core\Resource\StorageRepository $storageRepository */
			$storageRepository = $this->objectManager->get(StorageRepository::class);

			$storage = $storageRepository->findByUid($fileStorage);
			if (!$storage instanceof ResourceStorageInterface) {
				throw new \RuntimeException(sprintf("Resource storage with uid %d could not be found.", $fileStorage));
			}

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
			$newFileName = (new LocalDriver)->sanitizeFileName($prefix.$fileName);

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

		public function injectApplicationRepository(ApplicationRepository $applicationRepository): void
		{
			$this->applicationRepository = $applicationRepository;
		}

		public function injectPersistenceManager(PersistenceManager $persistenceManager): void
		{
			$this->persistenceManager = $persistenceManager;
		}

		public function injectApplicationFileService(ApplicationFileService $applicationFileService): void
		{
			$this->applicationFileService = $applicationFileService;
		}

		public function injectSignalSlotDispatcher(Dispatcher $signalSlotDispatcher): void
		{
			$this->signalSlotDispatcher = $signalSlotDispatcher;
		}

		public function injectPostingRepository(PostingRepository $postingRepository): void
		{
			$this->postingRepository = $postingRepository;
		}

		public function injectStatusRepository(StatusRepository $statusRepository): void
		{
			$this->statusRepository = $statusRepository;
		}
	}