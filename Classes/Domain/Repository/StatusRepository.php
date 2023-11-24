<?php

	namespace ITX\Jobapplications\Domain\Repository;

	use TYPO3\CMS\Core\Database\Schema\SchemaMigrator;
	use TYPO3\CMS\Core\Database\Schema\SqlReader;
	use TYPO3\CMS\Core\Utility\GeneralUtility;

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

	/**
	 * The repository for Applications
	 */
	class StatusRepository extends JobapplicationsRepository
	{
		/**
		 * Finds all with option of specifiying order
		 *
		 * @param string $orderBy
		 * @param string $order
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findAllWithOrder($orderBy = "name", $order = "ASC")
		{
			$query = $this->createQuery();
			$query->getQuerySettings()->setRespectStoragePage(false);
			$query->setOrderings([
									 $orderBy => $order
								 ]);

			return $query->execute();
		}

		/**
		 * Finds the one status that is declared as New-Status
		 *
		 * @return object
		 */
		public function findNewStatus()
		{
			$query = $this->createQuery();
			$query->getQuerySettings()->setRespectStoragePage(false);
			$query->matching($query->equals('is_new_status', 1));

			return $query->execute()->getFirst();
		}

		/**
		 * @param string $statusFile
		 * @param string $statusMmFile
		 * @param int    $pid
		 * @param int    $langUid
		 */
		public function generateStatus(string $statusFile, string $statusMmFile, int $pid, int $langUid): void
        {
			$file1 = GeneralUtility::getFileAbsFileName('EXT:jobapplications/Resources/Private/Sql/'.$statusFile);
			$file2 = GeneralUtility::getFileAbsFileName('EXT:jobapplications/Resources/Private/Sql/'.$statusMmFile);

			$queryDropStatus = $this->createQuery();
			$queryDropStatus->statement('DROP TABLE tx_jobapplications_domain_model_status');
			$queryDropStatus->execute();
			$queryDropMM = $this->createQuery();
			$queryDropMM->statement('DROP TABLE tx_jobapplications_domain_model_status_mm');
			$queryDropStatus->execute();

			$contentStatus = file_get_contents($file1);
			if ($contentStatus === false)
			{
				throw new \RuntimeException("Jobapplications: Error trying to load sql file at {$file1}");
			}
			$contentStatus = str_replace(['%pid%', '%lang%'], [$pid, $langUid], $contentStatus);
			$contentStatusMM = file_get_contents($file2);
			if ($contentStatusMM === false)
			{
				throw new \RuntimeException("Jobapplications: Error trying to load sql mm file at {$file2}");
			}

			$this->executeSqlImport($contentStatus);
			$this->executeSqlImport($contentStatusMM);
		}

        /**
         * Helper function
         *
         * @param string $fileContent
         *
         */
		public function executeSqlImport(string $fileContent)
		{
			/** @var SqlReader $sqlReader */
			$sqlReader = GeneralUtility::makeInstance(SqlReader::class);
			$statements = $sqlReader->getStatementArray($fileContent);

			/** @var SchemaMigrator $schemaMigrationService */
			$schemaMigrationService = GeneralUtility::makeInstance(SchemaMigrator::class);
			$result = $schemaMigrationService->importStaticData($statements, true);

            foreach ($result as $statement => $connectionResult) {
                if (!empty($connectionResult)) {
                    throw new \RuntimeException("Jobapplications: Error trying to import sql statement $statement: $connectionResult");
                }
            }
		}
	}

