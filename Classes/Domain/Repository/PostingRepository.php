<?php

	namespace ITX\Jobs\Domain\Repository;

	use ITX\Jobs\Domain\Model\Contact;
	use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
	use ITX\Jobs\Domain\Repository\RepoHelpers;

	include_once 'RepoHelpers.php';

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
		 * @param $category array
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findByCategory($categories)
		{
			$query = $this->createQuery();
			$statement = "SELECT * FROM tx_jobs_domain_model_posting 
    					  JOIN sys_category_record_mm ON tx_jobs_domain_model_posting.uid = sys_category_record_mm.uid_foreign ";

			$statement .= buildCategoriesToSQL($categories);

			$query->statement($statement);

			return $query->execute();
		}

		/**
		 * Gets all divisions
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findAllDivisions($categories = null)
		{
			$query = $this->createQuery();
			if (count($categories) == 0)
			{
				$query->statement(
					"SELECT DISTINCT division FROM tx_jobs_domain_model_posting 
							  WHERE deleted = 0 AND hidden = 0"
				);
			}
			else
			{
				$statement = "SELECT DISTINCT division AS division FROM tx_jobs_domain_model_posting
							  JOIN sys_category_record_mm ON tx_jobs_domain_model_posting.uid = sys_category_record_mm.uid_foreign 
							  WHERE deleted = 0 AND hidden = 0 ";
				$statement .= buildCategoriesToSQL($categories);

				$query->statement($statement);

			}

			return $query->execute(true);
		}

		/**
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findAllCareerLevels($categories = null)
		{
			$query = $this->createQuery();

			if (count($categories) == 0)
			{
				$query->statement(
					"SELECT DISTINCT career_level AS careerLevel FROM tx_jobs_domain_model_posting 
							  WHERE deleted = 0 AND hidden = 0");

			}
			else
			{
				$statement = "SELECT DISTINCT career_level AS careerLevel FROM tx_jobs_domain_model_posting
							  JOIN sys_category_record_mm ON tx_jobs_domain_model_posting.uid = sys_category_record_mm.uid_foreign 
							  WHERE deleted = 0 AND hidden = 0 ";

				$statement .= buildCategoriesToSQL($categories);

				$query->statement($statement);
			}

			return $query->execute(true);
		}

		/**
		 * @return array
		 */
		public function findAllEmploymentTypes($categories = null)
		{
			$query = $this->createQuery();

			if (count($categories) == 0)
			{
				$query->statement(
					"SELECT DISTINCT employment_type AS employmentType FROM tx_jobs_domain_model_posting 
							  WHERE deleted = 0 AND hidden = 0");
			}
			else
			{
				$statement = "SELECT DISTINCT employment_type AS employmentType FROM tx_jobs_domain_model_posting
								JOIN sys_category_record_mm ON tx_jobs_domain_model_posting.uid = sys_category_record_mm.uid_foreign 
								WHERE deleted = 0 AND hidden = 0 ";
				$statement .= buildCategoriesToSQL($categories);

				$query->statement($statement);
			}

			return $query->execute(true);
		}

		/**
		 * @param $division       String
		 * @param $careerLevel    String
		 * @param $employmentType String
		 * @param location          int
		 * @param category          int
		 *
		 *
		 * @return array
		 */
		public function findByFilter($division, $careerLevel, $employmentType, $location, $categories)
		{
			$divisionSQL = "";
			$careerLevelSQL = "";
			$employmentTypeSQL = "";
			$locationSQL = "";
			$categorySQL = "";

			$baseSQL = "SELECT * FROM tx_jobs_domain_model_posting WHERE deleted = 0 AND hidden = 0";

			if (count($categories) > 0)
			{
				$baseSQL = "SELECT * FROM tx_jobs_domain_model_posting
    						JOIN sys_category_record_mm ON tx_jobs_domain_model_posting.uid = sys_category_record_mm.uid_foreign 
							WHERE deleted = 0 AND hidden = 0 ";

				$categorySQL = buildCategoriesToSQL($categories);
			}

			if ($division)
			{
				$divisionSQL = "AND division = \"$division\"";
			}
			if ($careerLevel)
			{
				$careerLevelSQL = "AND career_level = \"$careerLevel\"";
			}
			if ($employmentType)
			{
				$employmentTypeSQL = "AND employment_type = \"$employmentType\"";
			}
			if ($location != 0)
			{
				$locationSQL = "AND location = $location";
			}
			$query = $this->createQuery();

			$query->statement(
				$baseSQL." ".$divisionSQL." ".$careerLevelSQL." ".$employmentTypeSQL." ".$locationSQL." ".$categorySQL
			);

			return $query->execute();

		}

		/**
		 * @param $contact int Contact Uid
		 * @param $orderBy string
		 * @param $order string
		 */
		public function findByContact($contact, $orderBy = "title", $order = "ASC")
		{
			$query = $this->createQuery();

			$query->statement("SELECT * FROM tx_jobs_domain_model_posting WHERE deleted = 0 AND contact = ".$contact."  ORDER BY ".$orderBy." ".$order);

			return $query->execute();
		}

		/**
		 * Returns all objects of this repository.
		 *
		 * @return QueryResultInterface|array
		 */
		public function findAllWithOrder($orderBy = "title", $order = "ASC")
		{
			$query = $this->createQuery();

			$query->statement("SELECT * FROM tx_jobs_domain_model_posting WHERE deleted = 0 ORDER BY ".$orderBy." ".$order);

			return $query->execute();
		}
	}
