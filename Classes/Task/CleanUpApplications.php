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
	class CleanUpApplications extends \TYPO3\CMS\Scheduler\Task\AbstractTask
	{
		public $months = null;

		/**
		 * This is the main method that is called when a task is executed
		 * Should return TRUE on successful execution, FALSE on error.
		 *
		 * @return bool Returns TRUE on successful execution, FALSE on error
		 */
		public function execute()
		{
			DebuggerUtility::var_dump($this->months);
			$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
			$persistenceManager = $objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class);
			$applicationRepository = $objectManager->get(\ITX\Jobs\Domain\Repository\ApplicationRepository::class);

			$now = new \DateTime();
			$timestamp = $now->modify("-".$this->months." months")->getTimestamp();

			$applications = $applicationRepository->findOlderThan($timestamp);
			$resultCount = count($applications);

			DebuggerUtility::var_dump($applications);

			foreach ($applications as $application)
			{
				$persistenceManager->remove($application);
			}

			if ($resultCount > 0)
			{
				$persistenceManager->persistAll();
			}

			$this->logger->info('[ITX\\Jobs\\Task\\CleanUpApplications]: '.$resultCount.' Applications deleted.');

			return true;
		}
	}