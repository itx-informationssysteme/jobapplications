<?php

	namespace ITX\Jobs\Domain\Repository;

	use ITX\Jobs\Domain\Model\Contact;
	use TYPO3\CMS\Core\Database\ConnectionPool;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
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
		 * Helper function for finding postings by category
		 *
		 * @param $category array
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findByCategory(array $categories)
		{
			$qb = getQueryBuilder("tx_jobs_domain_model_posting");

			$qb
				->select("*")
				->from("tx_jobs_domain_model_posting")
				->join("tx_jobs_domain_model_posting", "sys_category_record_mm", "sys_category_record_mm", "tx_jobs_domain_model_posting.uid = sys_category_record_mm.uid_foreign");

			$query = $this->createQuery();

			$qb = buildCategoriesToSQL($categories, $qb);

			$query->statement($qb->getSQL());

			return $query->execute();
		}

		/**
		 * Gets all divisions
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findAllDivisions(array $categories = null)
		{
			$qb = getQueryBuilder("tx_jobs_domain_model_posting");
			$query = $this->createQuery();
			if (count($categories) == 0)
			{
				$qb
					->select("division")
					->groupBy("division")
					->from("tx_jobs_domain_model_posting");
			}
			else
			{
				$qb
					->select("division")
					->groupBy("division")
					->from("tx_jobs_domain_model_posting")
					->join("tx_jobs_domain_model_posting", "sys_category_record_mm",
						   "sys_category_record_mm", $qb->expr()->eq("tx_jobs_domain_model_posting.uid",
																	 "sys_category_record_mm.uid_foreign"));
				$qb = buildCategoriesToSQL($categories, $qb);
			}
			$query->statement($qb->getSQL());

			return $query->execute(true);
		}

		/**
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findAllCareerLevels(array $categories = null)
		{
			$qb = getQueryBuilder("tx_jobs_domain_model_posting");

			$query = $this->createQuery();
			if (count($categories) == 0)
			{
				$qb
					->select("career_level AS careerLevel")
					->groupBy("careerLevel")
					->from("tx_jobs_domain_model_posting");
			}
			else
			{
				$qb
					->select("career_level AS careerLevel")
					->groupBy("careerLevel")
					->from("tx_jobs_domain_model_posting")
					->join("tx_jobs_domain_model_posting", "sys_category_record_mm",
						   "sys_category_record_mm", $qb->expr()->eq("tx_jobs_domain_model_posting.uid",
																	 "sys_category_record_mm.uid_foreign"));
				$qb = buildCategoriesToSQL($categories, $qb);
			}
			$query->statement($qb->getSQL());

			return $query->execute(true);
		}

		/**
		 * @return array
		 */
		public function findAllEmploymentTypes(array $categories = null)
		{
			$qb = getQueryBuilder("tx_jobs_domain_model_posting");

			$query = $this->createQuery();
			if (count($categories) == 0)
			{
				$qb
					->select("employment_type AS employmentType")
					->groupBy("employmentType")
					->from("tx_jobs_domain_model_posting");
			}
			else
			{
				$qb
					->select("employment_type AS employmentType")
					->groupBy("employmentType")
					->from("tx_jobs_domain_model_posting")
					->join("tx_jobs_domain_model_posting", "sys_category_record_mm",
						   "sys_category_record_mm", $qb->expr()->eq("tx_jobs_domain_model_posting.uid",
																	 "sys_category_record_mm.uid_foreign"));
				$qb = buildCategoriesToSQL($categories, $qb);

			}

			$query->statement($qb->getSQL());

			return $query->execute(true);
		}

		/**
		 * @param $division       String
		 * @param $careerLevel    String
		 * @param $employmentType String
		 * @param $location       int
		 * @param $categories     array
		 *
		 *
		 * @return array
		 */
		public function findByFilter(string $division, string $careerLevel, string $employmentType, int $location, array $categories)
		{
			$qb = getQueryBuilder("tx_jobs_domain_model_posting");

			if (count($categories) > 0)
			{
				$qb
					->select("*")
					->from("tx_jobs_domain_model_posting")
					->join("tx_jobs_domain_model_posting", "sys_category_record_mm",
						   "sys_category_record_mm",
						   "tx_jobs_domain_model_posting.uid = sys_category_record_mm.uid_foreign")
					->where($qb->expr()->eq("deleted", 0))
					->andWhere($qb->expr()->eq("hidden", 0));

				$qb = buildCategoriesToSQL($categories, $qb);

			}
			else
			{
				$qb = getQueryBuilder("tx_jobs_domain_model_posting");
				$qb
					->select("*")
					->from("tx_jobs_domain_model_posting")
					->where($qb->expr()->eq("deleted", 0))
					->andWhere($qb->expr()->eq("hidden", 0));
			}

			if ($division)
			{
				$qb->andWhere($qb->expr()->eq("division", "\"$division\""));
			}
			if ($careerLevel)
			{
				$qb->andWhere($qb->expr()->eq("career_level", "\"$careerLevel\""));
			}
			if ($employmentType)
			{
				$qb->andWhere($qb->expr()->eq("employment_type", "\"".$employmentType."\""));
			}
			if ($location != -1)
			{
				$qb->andWhere($qb->expr()->eq("location", $location));
			}
			$query = $this->createQuery();

			$query->statement($qb->getSQL());

			return $query->execute();
		}

		/**
		 * @param $contact int Contact Uid
		 * @param $orderBy string
		 * @param $order   string
		 */
		public function findByContact(int $contact, string $orderBy = "title", string $order = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING)
		{
			$query = $this->createQuery();
			$query->getQuerySettings()->setRespectStoragePage(false);

			$query->matching($query->equals("contact.uid", $contact));
			$query->setOrderings([$orderBy => $order]);

			return $query->execute();
		}

		/**
		 * Returns all objects of this repository.
		 *
		 * @return QueryResultInterface|array
		 */
		public function findAllWithOrder(string $orderBy = "title", string $order = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING)
		{
			$query = $this->createQuery();
			$query->getQuerySettings()->setRespectStoragePage(false);

			$query->setOrderings([
									 $orderBy => $order
								 ]);

			return $query->execute();
		}
	}
