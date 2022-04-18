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
	use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
	use TYPO3\CMS\Core\DataHandling\Model\RecordStateFactory;
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
			return 'txJobapplicationsMultipleLocations';
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
			$updateNeeded = false;
			// Check if the database table even exists
			if ($this->checkIfPostingsHasLocations()) {
				$updateNeeded = true;
			}
			return $updateNeeded;
		}

		public function updateNecessary(): bool
		{
			// TODO: Implement updateNecessary() method.
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
		protected function checkIfPostingsHasLocations(): bool
		{
			// wenn in Location etwas anderes als 0 drin steht und Locations nicht existiert oder nichts drinsteht

			$connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

			// Wenn es location gar nicht mehr gibt, hör auf
			$tableColumns = $connectionPool->getSchemaManager()->listTableColumns($this->tablePosting);
			if (!isset($tableColumns[$this->oldFieldName])) {
				return false;
			}

			$queryBuilder = $connectionPool->getQueryBuilderForTable($this->tablePosting);
			$queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));

			// Wenn es Felder gibt, wo bei location etwas drin steht, aber in locations nicht
			$numberOfEntries = $queryBuilder
				->count('uid')
				->from($this->tablePosting)
				->where(
					$queryBuilder->expr()->and(
						$queryBuilder->expr()->neq($this->oldFieldName, $queryBuilder->createNamedParameter(0)),
						$queryBuilder->expr()->eq($this->oldFieldName, $queryBuilder->createNamedParameter(0))
					)
				)
				->execute()
				->fetchColumn();
			return $numberOfEntries > 0;
		}
	}