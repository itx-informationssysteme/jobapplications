<?php

	namespace ITX\Jobapplications\Hooks;

	use ITX\Jobapplications\Domain\Model\Application;
	use ITX\Jobapplications\Domain\Repository\ApplicationRepository;
	use ITX\Jobapplications\Service\ApplicationFileService;
use TYPO3\CMS\Core\DataHandling\DataHandler;
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
		protected ApplicationFileService $applicationFileService;
		protected ApplicationRepository $applicationRepository;
		protected DataMapper $dataMapper;

		public function __construct()
		{
            // NOTE: This has been disabled for now, as there is no normal dependency injection available here
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
            return;
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

				$path = $this->applicationFileService->getApplicantFolder($application);
				$fileStorage = $this->applicationFileService->getFileStorage($application);
				$this->applicationFileService->deleteApplicationFolder($path, $fileStorage);
			}
		}
	}
