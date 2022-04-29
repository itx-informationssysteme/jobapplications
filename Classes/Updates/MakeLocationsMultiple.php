<?php

	declare(strict_types=1);

	namespace ITX\Jobapplications\Updates;

	/**
	 * This file is part of the "news" Extension for TYPO3 CMS.
	 *
	 * For the full copyright and license information, please read the
	 * LICENSE.txt file that was distributed with this source code.
	 */

	use TYPO3\CMS\Core\Database\ConnectionPool;
	use TYPO3\CMS\Core\Database\Query\QueryBuilder;
	use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
	use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

	/**
	 * Migrate empty slugs
	 */
	class MakeLocationsMultiple implements UpgradeWizardInterface
	{
		protected string $tableMm = 'tx_jobapplications_postings_locations_mm';
		protected string $tablePosting = 'tx_jobapplications_domain_model_posting';
		protected $oldFieldName = 'location';
		protected $newFieldName = 'locations';

		public function getIdentifier(): string
		{
			return 'jobapplications_makeLocationsMultiple';
		}

		public function getTitle(): string
		{
			return 'Make posting locations ready for multiple locations';
		}

		public function getDescription(): string
		{
			return 'Move locations of already present posting datasets in the mm table';
		}

		public function executeUpdate(): bool
		{
			// Get Postings table
			$connectionPostings = GeneralUtility::makeInstance(ConnectionPool::class)
												->getConnectionForTable($this->tablePosting);
			/** @var  QueryBuilder $queryBuilderPostings */
			$queryBuilderPostings = $connectionPostings->createQueryBuilder();
			$queryBuilderPostings->getRestrictions()->removeAll();

			// Get Mm table
			$connectionMm = GeneralUtility::makeInstance(ConnectionPool::class)
										  ->getConnectionForTable($this->tableMm);

			// Get all postings where location is not 0 and locations is 0
			$statement = $queryBuilderPostings->select('uid', $this->oldFieldName)
											  ->from($this->tablePosting)
											  ->where(
												  $queryBuilderPostings->expr()->neq($this->oldFieldName, 0),
												  $queryBuilderPostings->expr()->eq($this->newFieldName, 0)
											  )
											  ->execute();

			while ($record = $statement->fetch())
			{
				// Create mm entry
				/** @var QueryBuilder $queryBuilderMm */
				$queryBuilderMm = $connectionMm->createQueryBuilder();
				$queryBuilderMm->getRestrictions()->removeAll();
				$queryBuilderMm->insert($this->tableMm)
					->values([
								'uid_local' =>  (int)$record['uid'],
								'uid_foreign' => (int)$record[$this->oldFieldName],
								'sorting' => 1,
								'sorting_foreign' => 0
							 ])
					->execute();

				// Update locations field
				/** @var  QueryBuilder $queryBuilderPostings */
				$queryBuilderPostings = $connectionPostings->createQueryBuilder();

				$queryBuilderPostings->update($this->tablePosting)
									 ->where(
										 $queryBuilderPostings->expr()->eq(
											 'uid',
											 $queryBuilderPostings->createNamedParameter($record['uid'], \PDO::PARAM_INT)
										 )
									 )
									 ->set('locations', 1);
				//$databaseQueries[] = $queryBuilderPostings->getSQL();
				$queryBuilderPostings->execute();
			}

			return true;
		}

		public function updateNecessary(): bool
		{
			$updateNeeded = false;

			if ($this->checkIfNeeded())
			{
				$updateNeeded = true;
			}

			return $updateNeeded;
		}

		public function getPrerequisites(): array
		{
			return [
				DatabaseUpdatedPrerequisite::class
			];
		}

		/**
		 * Check if there are postings entries with locations
		 *
		 * @return bool
		 * @throws \InvalidArgumentException
		 */
		protected function checkIfNeeded(): bool
		{
			/** @var  $connectionPool ConnectionPool */
			$connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
			$connection = $connectionPool->getConnectionForTable($this->tablePosting);

			// Stop if there is no location field
			$tableColumns = $connection->getSchemaManager()->listTableColumns($this->tablePosting);
			if (!isset($tableColumns[$this->oldFieldName]))
			{
				return false;
			}

			$queryBuilder = $connectionPool->getQueryBuilderForTable($this->tablePosting);
			$queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));

			// Are there postings with associated locations?
			$numberOfEntries = $queryBuilder
				->count('uid')
				->from($this->tablePosting)
				->where(
					$queryBuilder->expr()->neq($this->oldFieldName, $queryBuilder->createNamedParameter(0))
				)
				->execute()
				->fetchOne();

			return $numberOfEntries > 0;
		}
	}