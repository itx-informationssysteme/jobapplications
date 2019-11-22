<?php

	namespace ITX\Jobs\Domain\Repository;

	use TYPO3\CMS\Core\Core\Environment;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Core\Database\Schema\SchemaMigrator;
	use TYPO3\CMS\Core\Database\Schema\SqlReader;
	use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
	use TYPO3\CMS\Core\Utility\PathUtility;
	use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

	/***
	 *
	 * This file is part of the "Jobs" Extension for TYPO3 CMS.
	 *
	 * For the full copyright and license information, please read the
	 * LICENSE.txt file that was distributed with this source code.
	 *
	 *  (c) 2019 Stefanie Döll, it.x informationssysteme gmbh
	 *           Benjamin Jasper, it.x informationssysteme gmbh
	 *
	 ***/

	/**
	 * The repository for Applications
	 */
	class StatusRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
	{
		/**
		 * Finds all followers of given status
		 *
		 * @param $status
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findFollowers($status)
		{
			$query = $this->createQuery();
			$query->statement("SELECT * FROM tx_jobs_domain_model_status
    									JOIN tx_jobs_domain_model_status_mm
    									ON tx_jobs_domain_model_status.uid = tx_jobs_domain_model_status_mm.uid_foreign
										WHERE uid_local = ".$status." ORDER BY name");

			return $query->execute();
		}

		/**
		 * @param $extTablesStaticSqlRelFile
		 */
		public function generateStatusEn($statusFile, $statusMmFile, int $pid)
		{
			$file1 = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName("EXT:jobs/Resources/Private/Sql/".$statusFile);
			$file2 = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName("EXT:jobs/Resources/Private/Sql/".$statusMmFile);

			$contentStatus = file_get_contents($file1);
			$contentStatus = str_replace("%pid%", $pid, $contentStatus);
			$contentStatusMM = file_get_contents($file2);

			$this->executeSqlImport($contentStatus);
			$this->executeSqlImport($contentStatusMM);
		}

		/**
		 * Helper function
		 *
		 * @param $fileContent
		 */
		public function executeSqlImport($fileContent)
		{
			$sqlReader = GeneralUtility::makeInstance(SqlReader::class);
			$statements = $sqlReader->getStatementArray($fileContent);
			DebuggerUtility::var_dump($statements);
			$schemaMigrationService = GeneralUtility::makeInstance(SchemaMigrator::class);
			$schemaMigrationService->importStaticData($statements, true);
		}
	}

