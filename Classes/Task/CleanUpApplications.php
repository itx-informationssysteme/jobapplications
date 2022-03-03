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

	use ITX\Jobapplications\Domain\Repository\ApplicationRepository;
	use ITX\Jobapplications\Service\ApplicationFileService;
	use TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException;
	use TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException;
	use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
	use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
	use TYPO3\CMS\Scheduler\Task\AbstractTask;

	/**
	 * Task for deleting all applications older than a specific amount of time
	 *
	 * @package ITX\Jobapplications
	 */
	class CleanUpApplications extends AbstractTask
	{
		public int $days = 90;
		public int $status = 0;

		protected PersistenceManager $persistenceManager;
		protected ApplicationRepository $applicationRepository;
		protected ApplicationFileService $applicationFileService;

		public function __construct(PersistenceManager $persistenceManager,
									ApplicationRepository $applicationRepository,
									ApplicationFileService $applicationFileService)
		{
			$this->persistenceManager = $persistenceManager;
			$this->applicationRepository = $applicationRepository;
			$this->applicationFileService = $applicationFileService;

			parent::__construct();
		}

		/**
		 * This is the main method that is called when a task is executed
		 * Should return TRUE on successful execution, FALSE on error.
		 *
		 * @return bool Returns TRUE on successful execution, FALSE on error
		 * @throws InvalidFileNameException
		 * @throws InsufficientFolderAccessPermissionsException
		 * @throws InvalidQueryException
		 */
		public function execute()
		{
			$now = new \DateTime();
			$timestamp = $now->modify("-".$this->days." days")->getTimestamp();

			if ($this->status = 1)
			{
				$applications = $this->applicationRepository->findOlderThan($timestamp, true);
			}
			else
			{
				$applications = $this->applicationRepository->findOlderThan($timestamp);
			}

			$resultCount = count($applications);

			foreach ($applications as $application)
			{
				$fileStorage = $this->applicationFileService->getFileStorage($application);
				$this->applicationRepository->remove($application);

				$this->applicationFileService->deleteApplicationFolder($this->applicationFileService->getApplicantFolder($application), $fileStorage);
			}

			if ($resultCount > 0)
			{
				$this->persistenceManager->persistAll();
			}

			$this->logger->info('[ITX\\Jobapplications\\Task\\CleanUpApplications]: '.$resultCount.' Applications deleted.');

			return true;
		}
	}