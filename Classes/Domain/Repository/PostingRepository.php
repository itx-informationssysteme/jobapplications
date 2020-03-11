<?php

	namespace ITX\Jobapplications\Domain\Repository;

	use ITX\Jobapplications\Domain\Model\Contact;
	use TYPO3\CMS\Core\Database\ConnectionPool;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use ITX\Jobapplications\Domain\Repository\RepoHelpers;

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
	 * The repository for Postings
	 */
	class PostingRepository extends \ITX\Jobapplications\Domain\Repository\JobapplicationsRepository
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
			$qb = parent::getQueryBuilder("tx_jobapplications_domain_model_posting");

			$qb
				->select("*")
				->from("tx_jobapplications_domain_model_posting")
				->join("tx_jobapplications_domain_model_posting", "sys_category_record_mm", "sys_category_record_mm", "tx_jobapplications_domain_model_posting.uid = sys_category_record_mm.uid_foreign");

			$query = $this->createQuery();

			$qb = parent::buildCategoriesToSQL($categories, $qb);

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
			$qb = parent::getQueryBuilder("tx_jobapplications_domain_model_posting");
			$query = $this->createQuery();
			if (count($categories) == 0)
			{
				$qb
					->select("division")
					->groupBy("division")
					->from("tx_jobapplications_domain_model_posting");
			}
			else
			{
				$qb
					->select("division")
					->groupBy("division")
					->from("tx_jobapplications_domain_model_posting")
					->where($qb->expr()->in("pid", $query->getQuerySettings()->getStoragePageIds()))
					->join("tx_jobapplications_domain_model_posting", "sys_category_record_mm",
						   "sys_category_record_mm", $qb->expr()->eq("tx_jobapplications_domain_model_posting.uid",
																	 "sys_category_record_mm.uid_foreign"));
				$qb = parent::buildCategoriesToSQL($categories, $qb);
			}
			$query->statement($qb->getSQL());

			return $query->execute(true);
		}

		/**
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findAllCareerLevels(array $categories = null)
		{
			$qb = parent::getQueryBuilder("tx_jobapplications_domain_model_posting");

			$query = $this->createQuery();
			if (count($categories) == 0)
			{
				$qb
					->select("career_level AS careerLevel")
					->groupBy("careerLevel")
					->from("tx_jobapplications_domain_model_posting");
			}
			else
			{
				$qb
					->select("career_level AS careerLevel")
					->groupBy("careerLevel")
					->from("tx_jobapplications_domain_model_posting")
					->where($qb->expr()->in("pid", $query->getQuerySettings()->getStoragePageIds()))
					->join("tx_jobapplications_domain_model_posting", "sys_category_record_mm",
						   "sys_category_record_mm", $qb->expr()->eq("tx_jobapplications_domain_model_posting.uid",
																	 "sys_category_record_mm.uid_foreign"));
				$qb = parent::buildCategoriesToSQL($categories, $qb);
			}
			$query->statement($qb->getSQL());

			return $query->execute(true);
		}

		/**
		 * @return array
		 */
		public function findAllEmploymentTypes(array $categories = null)
		{
			$qb = parent::getQueryBuilder("tx_jobapplications_domain_model_posting");

			$query = $this->createQuery();
			if (count($categories) == 0)
			{
				$qb
					->select("employment_type AS employmentType")
					->groupBy("employmentType")
					->from("tx_jobapplications_domain_model_posting");
			}
			else
			{
				$qb
					->select("employment_type AS employmentType")
					->groupBy("employmentType")
					->from("tx_jobapplications_domain_model_posting")
					->where($qb->expr()->in("pid", $query->getQuerySettings()->getStoragePageIds()))
					->join("tx_jobapplications_domain_model_posting", "sys_category_record_mm",
						   "sys_category_record_mm", $qb->expr()->eq("tx_jobapplications_domain_model_posting.uid",
																	 "sys_category_record_mm.uid_foreign"));
				$qb = parent::buildCategoriesToSQL($categories, $qb);

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
			$qb = parent::getQueryBuilder("tx_jobapplications_domain_model_posting");

			if (count($categories) > 0)
			{
				$qb
					->select("*")
					->from("tx_jobapplications_domain_model_posting")
					->join("tx_jobapplications_domain_model_posting", "sys_category_record_mm",
						   "sys_category_record_mm",
						   "tx_jobapplications_domain_model_posting.uid = sys_category_record_mm.uid_foreign")
					->where($qb->expr()->eq("deleted", 0))
					->andWhere($qb->expr()->eq("hidden", 0));

				$qb = parent::buildCategoriesToSQL($categories, $qb);

			}
			else
			{
				$qb = parent::getQueryBuilder("tx_jobapplications_domain_model_posting");
				$qb
					->select("*")
					->from("tx_jobapplications_domain_model_posting")
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
		 * Only for use in Backend context
		 *
		 * @param $contact int Contact Uid
		 * @param $orderBy string
		 * @param $order   string
		 */
		public function findByContact(int $contact, string $orderBy = "title", string $order = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING)
		{
			$query = $this->createQuery();
			$query->getQuerySettings()->setRespectStoragePage(false)
				  ->setIgnoreEnableFields(true);

			$query->matching($query->equals("contact.uid", $contact));
			$query->setOrderings([$orderBy => $order]);

			return $query->execute();
		}

		/**
		 * Returns all objects of this repository. Only for use in backend context
		 *
		 * @return QueryResultInterface|array
		 */
		public function findAllWithOrderIgnoreEnable(string $orderBy = "title", string $order = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING)
		{
			$query = $this->createQuery();
			$query->getQuerySettings()->setRespectStoragePage(false)->setIgnoreEnableFields(true);

			$query->setOrderings([
									 $orderBy => $order
								 ]);

			return $query->execute();
		}

		/**
		 * Returns !all! objects of this repository, no matter if hidden or deleted.
		 *
		 * @return QueryResultInterface|array
		 */
		public function findAllIncludingHiddenAndDeleted()
		{
			$query = $this->createQuery();
			$query->getQuerySettings()
				  ->setRespectStoragePage(false)
				  ->setIgnoreEnableFields(true)
				  ->setIncludeDeleted(true);

			return $query->execute();
		}
	}
