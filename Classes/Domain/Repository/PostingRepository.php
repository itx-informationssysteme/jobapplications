<?php

	namespace ITX\Jobs\Domain\Repository;

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
	 * The repository for Postings
	 */
	class PostingRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
	{
		/**
		 * Helper function for finding all relevant categories
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findCategories(): array
		{
			$query = $this->createQuery();

			$query->statement(
				"SELECT DISTINCT title,sys_category_record_mm.uid_local AS uid FROM sys_category 
    						JOIN sys_category_record_mm ON sys_category_record_mm.uid_local = sys_category.uid 
							WHERE sys_category_record_mm.tablenames = 'tx_jobs_domain_model_posting'"
			);

			return $query->execute(true);
		}

		/**
		 * Helper function for finding postings by category
		 *
		 * @param $category int
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findByCategory($category)
		{
			$query = $this->createQuery();

			$query->statement(
				"SELECT * FROM tx_jobs_domain_model_posting 
    						JOIN sys_category_record_mm ON tx_jobs_domain_model_posting.uid = sys_category_record_mm.uid_foreign
							WHERE sys_category_record_mm.uid_local = ".$category
			);

			return $query->execute();
		}

		/**
		 * Gets all divisions
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findAllDivisions() {
			$query = $this->createQuery();

			$query->statement(
				"SELECT DISTINCT division FROM tx_jobs_domain_model_posting 
							WHERE deleted = 0 AND hidden = 0"
			);

			return $query->execute(true);
		}

		/**
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findAllCareerLevels() {
			$query = $this->createQuery();

			$query->statement(
				"SELECT DISTINCT career_level AS careerLevel FROM tx_jobs_domain_model_posting 
							WHERE deleted = 0 AND hidden = 0"
			);

			return $query->execute(TRUE);
		}

		/**
		 * @return array
		 */
		public function findAllEmploymentTypes() {
			$query = $this->createQuery();

			$query->statement(
				"SELECT DISTINCT employment_type AS employmentType FROM tx_jobs_domain_model_posting 
							WHERE deleted = 0 AND hidden = 0"
			);

			return $query->execute(TRUE);
		}


		/**
		 * @param $division String
		 * @param $careerLevel String
		 * @param $employmentType String
		 *
		 * @return array
		 */
		public function findByFilter($division, $careerLevel, $employmentType) {
			$divisionSQL = "";
			$careerLevelSQL = "";
			$employmentTypeSQL = "";

			if ($division) {
				$divisionSQL = "AND division = \"$division\"";
			}
			if($careerLevel) {
				$careerLevelSQL = "AND career_level = \"$careerLevel\"";
			}
			if($employmentType) {
				$employmentTypeSQL = "AND employment_type = \"$employmentType\"";
			}
			$query = $this->createQuery();

			$query->statement(
				"SELECT * FROM tx_jobs_domain_model_posting 
							WHERE deleted = 0 AND hidden = 0 ".$divisionSQL." ".$careerLevelSQL." ".$employmentTypeSQL
			);

			return $query->execute();
		}
	}
