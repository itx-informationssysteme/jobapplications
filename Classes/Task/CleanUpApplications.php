<?php

	namespace ITX\Jobs\Task;

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
		public $days = null;
		public $status = 0;

		/**
		 * This is the main method that is called when a task is executed
		 * Should return TRUE on successful execution, FALSE on error.
		 *
		 * @return bool Returns TRUE on successful execution, FALSE on error
		 */
		public function execute()
		{
			$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
			$persistenceManager = $objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class);
			$applicationRepository = $objectManager->get(\ITX\Jobs\Domain\Repository\ApplicationRepository::class);
			$applicationFileService = $objectManager->get(\ITX\Jobs\Service\ApplicationFileService::class);

			$now = new \DateTime();
			$timestamp = $now->modify("-".$this->days." days")->getTimestamp();

			if ($status = 1)
			{
				$applications = $applicationRepository->findOlderThan($timestamp, true);
			}
			else
			{
				$applications = $applicationRepository->findOlderThan($timestamp);
			}

			$resultCount = count($applications);

			foreach ($applications as $application)
			{
				$applicationRepository->remove($application);
				$applicationFileService->deleteApplicationFolder($applicationFileService->getApplicantFolder($application));
			}

			if ($resultCount > 0)
			{
				$persistenceManager->persistAll();
			}

			$this->logger->info('[ITX\\Jobs\\Task\\CleanUpApplications]: '.$resultCount.' Applications deleted.');

			return true;
		}
	}