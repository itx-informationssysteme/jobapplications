<?php

	namespace ITX\Jobs\Task;

	use TYPO3\CMS\Extbase\Object\ObjectManager;
	use TYPO3\CMS\Core\Database\ConnectionPool;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

	/**
	 * Task for deleting all applications older than a specific amount of time
	 *
	 * @package ITX\Jobs
	 */
	class AnonymizeApplications extends \TYPO3\CMS\Scheduler\Task\AbstractTask
	{
		public $days = null;
		public $status = 0;

		/**
		 * This is the main method that is called when a task is executed
		 * Should return TRUE on successful execution, FALSE on error.
		 *
		 * @return bool Returns TRUE on successful execution, FALSE on error
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
		 */
		public function execute()
		{
			$anonymizeChars = "***";
			$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
			$persistenceManager = $objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class);
			$applicationRepository = $objectManager->get(\ITX\Jobs\Domain\Repository\ApplicationRepository::class);
			$applicationFileService = $objectManager->get(\ITX\Jobs\Service\ApplicationFileService::class);

			// Calculate Timestamp for how old the application must be to give to the repo
			$now = new \DateTime();
			$timestamp = $now->modify("-".$this->days." days")->getTimestamp();

			if ($status = 1)
			{
				$applications = $applicationRepository->findNotAnonymizedOlderThan($timestamp, true);
			}
			else
			{
				$applications = $applicationRepository->findNotAnonymizedOlderThan($timestamp);
			}

			$resultCount = count($applications);

			/* @var \ITX\Jobs\Domain\Model\Application $application */
			foreach ($applications as $application)
			{
				// Actual anonymization + deleting application files

				/* @var \ITX\Jobs\Service\ApplicationFileService $applicationFileService*/
				$applicationFileService->deleteApplicationFolder($applicationFileService->getApplicantFolder($application));

				$application->setFirstName($anonymizeChars);
				$application->setLastName($anonymizeChars);
				$application->setAddressStreetAndNumber($anonymizeChars);
				$application->setAddressAddition($anonymizeChars);
				$application->setAddressPostCode(0);
				$application->setEmail("anonymized@anonymized.anonymized");
				$application->setPhone($anonymizeChars);
				$application->setMessage($anonymizeChars);
				$application->setArchived(true);
				$application->setSalutation("");

				$persistenceManager->update($application);
			}

			if ($resultCount > 0)
			{
				$persistenceManager->persistAll();
			}

			$this->logger->info('[ITX\\Jobs\\Task\\AnonymizeApplications]: '.$resultCount.' Applications anonymized.');

			return true;
		}
	}