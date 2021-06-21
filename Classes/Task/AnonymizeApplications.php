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

	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Extbase\Object\ObjectManager;

	/**
	 * Task for deleting all applications older than a specific amount of time
	 *
	 * @package ITX\Jobapplications
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
			/* @var $objectManager ObjectManager */
			$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
			$persistenceManager = $objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class);
			$applicationRepository = $objectManager->get(\ITX\Jobapplications\Domain\Repository\ApplicationRepository::class);
			$applicationFileService = $objectManager->get(\ITX\Jobapplications\Service\ApplicationFileService::class);

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

			/* @var \ITX\Jobapplications\Domain\Model\Application $application */
			foreach ($applications as $application)
			{
				// Actual anonymization + deleting application files

				/* @var \ITX\Jobapplications\Service\ApplicationFileService $applicationFileService */
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
				$application->setSalaryExpectation($anonymizeChars);
				$application->setEarliestDateOfJoining(new \DateTime("@0"));

				$applicationRepository->update($application);
			}

			if ($resultCount > 0)
			{
				$persistenceManager->persistAll();
			}

			$this->logger->info('[ITX\\Jobapplications\\Task\\AnonymizeApplications]: '.$resultCount.' Applications anonymized.');

			return true;
		}
	}