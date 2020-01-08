<?php

	namespace ITX\Jobs\Domain\Repository;

	use ITX\Jobs\Domain\Model\Status;
	use TYPO3\CMS\Core\Core\Environment;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Core\Database\Schema\SchemaMigrator;
	use TYPO3\CMS\Core\Database\Schema\SqlReader;
	use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
	use TYPO3\CMS\Core\Utility\PathUtility;
	use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

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
	class StatusRepository extends \ITX\Jobs\Domain\Repository\JobsRepository
	{
		/**
		 * Finds all with option of specifiying order
		 *
		 * @param string $orderBy
		 * @param string $order
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findAllWithOrder(string $orderBy = "name", string $order = "ASC")
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
			$query->matching($query->equals("is_new_status", 1));

			return $query->execute()->getFirst();
		}

		/**
		 * @param $extTablesStaticSqlRelFile
		 */
		public function generateStatus(string $statusFile, string $statusMmFile, int $pid, int $langUid)
		{
			$file1 = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName("EXT:jobs/Resources/Private/Sql/".$statusFile);
			$file2 = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName("EXT:jobs/Resources/Private/Sql/".$statusMmFile);

			$queryDropStatus = $this->createQuery();
			$queryDropStatus->statement("DROP TABLE tx_jobs_domain_model_status");
			$queryDropStatus->execute();
			$queryDropMM = $this->createQuery();
			$queryDropMM->statement("DROP TABLE tx_jobs_domain_model_status_mm");
			$queryDropStatus->execute();

			$contentStatus = file_get_contents($file1);
			$contentStatus = str_replace("%pid%", $pid, $contentStatus);
			$contentStatus = str_replace("%lang%", $langUid, $contentStatus);
			$contentStatusMM = file_get_contents($file2);

			$this->executeSqlImport($contentStatus);
			$this->executeSqlImport($contentStatusMM);
		}

		/**
		 * Helper function
		 *
		 * @param $fileContent
		 */
		public function executeSqlImport(string $fileContent)
		{
			$sqlReader = GeneralUtility::makeInstance(SqlReader::class);
			$statements = $sqlReader->getStatementArray($fileContent);

			$schemaMigrationService = GeneralUtility::makeInstance(SchemaMigrator::class);
			$schemaMigrationService->importStaticData($statements, true);
		}

		/**
		 * @param $langIso string code as in language_isocode in sys_language table
		 *
		 * @return int uid of language
		 */
		public function findLangUid(string $langIso)
		{
			$query = $this->createQuery();
			$query->statement("SELECT DISTINCT uid FROM sys_language WHERE language_isocode = '$langIso'");

			$result = $query->execute(true)[0]['uid'];
			if ($result == null)
			{
				$result = -1;
			}

			return $result;
		}
	}

