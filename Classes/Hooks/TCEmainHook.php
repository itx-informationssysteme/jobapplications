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
		/**
		 * @param             $status
		 * @param             $table
		 * @param             $id
		 * @param array       $fieldArray
		 * @param DataHandler $pObj
		 *
		 * @throws \Exception
		 */
		public function processDatamap_afterDatabaseOperations($status, $table, $id, array $fieldArray, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj)
		{

			if ($table === 'tx_jobapplications_domain_model_posting')
			{
				$enabled = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class)
																 ->get('jobapplications', 'indexing_api');

				if ($enabled !== "1")
				{
					return;
				}

				if ($status === "new")
				{
					/** @var DataMapper $dataMapper */
					$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
					$dataMapper = $objectManager->get(DataMapper::class);

					$uid = $pObj->substNEWwithIDs[$id];

					$fieldArray['uid'] = $uid;

					$posting = $dataMapper->map(Posting::class, [$fieldArray]);

					$connector = new GoogleIndexingApiConnector();

					$connector->updateGoogleIndex($uid, false, $posting);
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
		public function processDatamap_postProcessFieldArray($command, $table, $uid, $value, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj)
		{
			if ($table === "tx_jobapplications_domain_model_posting")
			{
				$enabled = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class)
																 ->get('jobapplications', 'indexing_api');
				if ($enabled !== "1")
				{
					return;
				}

				if ($command === "update")
				{
					$connector = new GoogleIndexingApiConnector();
					if ($value['hidden'] === '1')
					{
						$connector->updateGoogleIndex($uid, true);
					}
					else
					{
						$connector->updateGoogleIndex($uid);
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
				$enabled = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class)
																 ->get('jobapplications', 'indexing_api');
				if ($enabled !== "1")
				{
					return;
				}

				$connector = new GoogleIndexingApiConnector();
				$connector->updateGoogleIndex($uid, true);
			}

			if ($table === "tx_jobapplications_domain_model_application")
			{
				/** @var ObjectManager $objectManager */
				$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
				/** @var ApplicationFileService $fileService */
				$fileService = $objectManager->get(ApplicationFileService::class);
				/** @var ApplicationRepository $applicationRepository */
				$applicationRepository = $objectManager->get(ApplicationRepository::class);

				if ($record['hidden'] === 1)
				{

					/** @var DataMapper $dataMapper */
					$dataMapper = $objectManager->get(DataMapper::class);
					$applications = $dataMapper->map(Application::class, [$record]);
					$application = $applications[0];
				}
				else
				{
					/** @var Application $application */
					$application = $applicationRepository->findByUid($uid);
				}

				$path = $fileService->getApplicantFolder($application);
				$fileService->deleteApplicationFolder($path);
			}
		}
	}