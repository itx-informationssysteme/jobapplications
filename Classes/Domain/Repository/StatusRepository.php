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
		public function generateStatus($statusFile, $statusMmFile, int $pid, int $langUid)
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
		public function executeSqlImport($fileContent)
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
		public function findLangUid($langIso)
		{
			$query = $this->createQuery();
			$query->statement("SELECT DISTINCT uid FROM sys_language WHERE language_isocode = '$langIso'");

			$result = $query->execute(TRUE)[0]['uid'];
			if($result == null) {
				$result = 0;
			}

			return $result;
		}
	}

