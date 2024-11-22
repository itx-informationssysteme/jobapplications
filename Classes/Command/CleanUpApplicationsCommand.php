<?php

	namespace ITX\Jobapplications\Command;

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
	use Psr\Log\LoggerInterface;
	use Symfony\Component\Console\Command\Command;
	use Symfony\Component\Console\Input\InputArgument;
	use Symfony\Component\Console\Input\InputOption;
	use TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException;
	use TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException;
	use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
	use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
	use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
	use ITX\Jobapplications\Service\ConfigurationLoaderService;

	/**
	 * Task for deleting all applications older than a specific amount of time
	 *
	 * @package ITX\Jobapplications
	 */
	class CleanUpApplicationsCommand extends Command
	{
		private LoggerInterface $logger;

		protected ConfigurationLoaderService $configurationLoaderService;
		protected PersistenceManager $persistenceManager;
		protected ApplicationRepository $applicationRepository;
		protected ApplicationFileService $applicationFileService;

		public function __construct(ConfigurationLoaderService	$configurationLoaderService,
									PersistenceManager     		$persistenceManager,
									ApplicationRepository  		$applicationRepository,
									ApplicationFileService 		$applicationFileService,
									LoggerInterface        		$logger)
		{
			$this->configurationLoaderService = $configurationLoaderService;
			$this->persistenceManager = $persistenceManager;
			$this->applicationRepository = $applicationRepository;
			$this->applicationFileService = $applicationFileService;
			$this->logger = $logger;

			parent::__construct();
		}

		public function configure()
		{
			$this
				->setDescription("Deletes applications that are older than the specified days.")
				->addArgument("days", InputArgument::OPTIONAL, "How old can an application be before is should be deleted?", 90)
				->addOption("withStatus", "s", InputOption::VALUE_NONE, "Should applications only be deleted if they are in an end status?");
		}

		/**
		 * This is the main method that is called when a task is executed
		 * Should return TRUE on successful execution, FALSE on error.
		 *
		 * @return int Returns TRUE on successful execution, FALSE on error
		 * @throws InvalidFileNameException
		 * @throws InsufficientFolderAccessPermissionsException
		 * @throws InvalidQueryException
		 * @throws IllegalObjectTypeException
		 */
		public function execute($input, $output): int
		{
			/*
			 *	Issue: 	Repositories currently can't be used in TYPO3 v13 inside commands,
			 * 			unless the ConfigurationManager is loaded in manually. This function
			 *			does that for us.
			 *	Link:	https://forge.typo3.org/issues/105616
			 */
			$this->configurationLoaderService->initCliEnvironment();
			
			$days = $input->getArgument('days') ?? 90;
			$withStatus = $input->getOption('withStatus') ?? false;

			$now = new \DateTime();
			$timestamp = $now->modify("-".$days." days")->getTimestamp();

			$applications = $this->applicationRepository->findOlderThan($timestamp, $withStatus);

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

			$this->logger->info('[ITX\\Jobapplications\\Task\\CleanUpApplications]: '.$resultCount.' applications deleted.');
			$output->writeln("$resultCount applications deleted.");

			return Command::SUCCESS;
		}
	}