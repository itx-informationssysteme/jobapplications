<?php

	namespace ITX\Jobapplications\Task;

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
	use TYPO3\CMS\Scheduler\Task\AbstractTask;
	use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
	use ITX\Jobapplications\Domain\Repository\ApplicationRepository;
	use ITX\Jobapplications\Service\ApplicationFileService;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Extbase\Object\ObjectManager;

	/**
	 * Task for deleting all applications older than a specific amount of time
	 *
	 * @package ITX\Jobapplications
	 */
	class CleanUpApplications extends AbstractTask
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
			$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
			$persistenceManager = $objectManager->get(PersistenceManager::class);
			$applicationRepository = $objectManager->get(ApplicationRepository::class);
			$applicationFileService = $objectManager->get(ApplicationFileService::class);

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
				$fileStorage = $applicationFileService->getFileStorage($application);
				$applicationRepository->remove($application);

				$applicationFileService->deleteApplicationFolder($applicationFileService->getApplicantFolder($application), $fileStorage);
			}

			if ($resultCount > 0)
			{
				$persistenceManager->persistAll();
			}

			$this->logger->info('[ITX\\Jobapplications\\Task\\CleanUpApplications]: '.$resultCount.' Applications deleted.');

			return true;
		}
	}