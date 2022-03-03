<?php

	namespace ITX\Jobapplications\Hooks;

	use ITX\Jobapplications\Domain\Model\Application;
	use ITX\Jobapplications\Domain\Model\Posting;
	use ITX\Jobapplications\Domain\Repository\ApplicationRepository;
	use ITX\Jobapplications\Service\ApplicationFileService;
	use ITX\Jobapplications\Utility\GoogleIndexingApiConnector;
	use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
	use TYPO3\CMS\Core\DataHandling\DataHandler;
	use TYPO3\CMS\Core\Utility\DebugUtility;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Extbase\Object\ObjectManager;
	use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;

	/**
	 * Class TCEmainHook
	 *
	 * Catches Record manipulation events
	 *
	 * @package ITX\Jobapplications\Hooks
	 */
	class TCEmainHook
	{
		protected GoogleIndexingApiConnector $connector;
		protected ApplicationFileService $applicationFileService;
		protected ApplicationRepository $applicationRepository;
		protected DataMapper $dataMapper;

		public function __construct()
		{
			/** @var ObjectManager $objectManager */
			$objectManager = GeneralUtility::makeInstance(ObjectManager::class);

			$this->connector = $objectManager->get(GoogleIndexingApiConnector::class);
			$this->applicationFileService = $objectManager->get(ApplicationFileService::class);
			$this->applicationRepository = $objectManager->get(ApplicationRepository::class);
			$this->dataMapper = $objectManager->get(DataMapper::class);
		}

		/**
		 * @param             $status
		 * @param             $table
		 * @param             $id
		 * @param array       $fieldArray
		 * @param DataHandler $pObj
		 *
		 * @throws \Exception
		 */
		public function processDatamap_afterDatabaseOperations($status, $table, $id, array $fieldArray, DataHandler $pObj): void
		{

			if ($table === 'tx_jobapplications_domain_model_posting')
			{
				$enabled = GeneralUtility::makeInstance(ExtensionConfiguration::class)
																 ->get('jobapplications', 'indexing_api');

				if ($enabled !== "1")
				{
					return;
				}

				if ($status === "new")
				{
					$uid = $pObj->substNEWwithIDs[$id];

					$fieldArray['uid'] = $uid;

					$posting = $this->dataMapper->map(Posting::class, [$fieldArray]);

					$this->connector->updateGoogleIndex($uid, false, $posting);
				}
			}
		}

		/**
		 * @param                                          $command
		 * @param                                          $table
		 * @param                                          $uid
		 * @param                                          $value
		 * @param \TYPO3\CMS\Core\DataHandling\DataHandler $pObj
		 *
		 * @throws \Exception
		 */
		public function processDatamap_postProcessFieldArray($command, $table, $uid, $value, DataHandler $pObj)
		{
			if ($table === "tx_jobapplications_domain_model_posting")
			{
				$enabled = GeneralUtility::makeInstance(ExtensionConfiguration::class)
																 ->get('jobapplications', 'indexing_api');
				if ($enabled !== "1")
				{
					return;
				}

				if ($command === "update")
				{
					if ($value['hidden'] === '1')
					{
						$this->connector->updateGoogleIndex($uid, true);
					}
					else
					{
						$this->connector->updateGoogleIndex($uid, false);
					}
				}
			}
		}

		/**
		 * @param string      $command
		 * @param string      $table
		 * @param             $id
		 * @param             $value
		 * @param bool        $commandIsProcessed
		 * @param DataHandler $dataHandler
		 * @param             $pasteUpdate
		 *
		 * @throws \Exception
		 */
		public function processCmdmap_deleteAction($table, $uid, array $record, &$recordWasDeleted, DataHandler $dataHandler)
		{
			if ($table === "tx_jobapplications_domain_model_posting")
			{
				$enabled = GeneralUtility::makeInstance(ExtensionConfiguration::class)
																 ->get('jobapplications', 'indexing_api');
				if ($enabled !== "1")
				{
					return;
				}

				$this->connector->updateGoogleIndex($uid, true);
			}

			if ($table === "tx_jobapplications_domain_model_application")
			{
				if ($record['hidden'] === 1)
				{
					$applications = $this->dataMapper->map(Application::class, [$record]);
					$application = $applications[0];
				}
				else
				{
					/** @var Application $application */
					$application = $this->applicationRepository->findByUid($uid);
				}

				$path = $this->fileService->getApplicantFolder($application);
				$fileStorage = $this->fileService->getFileStorage($application);
				$this->fileService->deleteApplicationFolder($path, $fileStorage);
			}
		}
	}