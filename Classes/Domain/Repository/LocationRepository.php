<?php

	namespace ITX\Jobs\Domain\Repository;

	use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

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
	 * The repository for Locations
	 */
	class LocationRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
	{
		/**
		 * Returns all objects of this repository.
		 *
		 * @param $categories array
		 *
		 * @return QueryResultInterface|array
		 */
		public function findAll(array $categories = null)
		{
			$qb = getQueryBuilder("tx_jobs_domain_model_location");
			$query = $this->createQuery();

			if (count($categories) == 0)
			{
				$qb->select("*")
				   ->from("tx_jobs_domain_model_location");
			}
			else
			{
				$sb = getQueryBuilder("tx_jobs_domain_model_posting");
				$sb
					->select("location")
					->from("tx_jobs_domain_model_posting")
					->join("tx_jobs_domain_model_posting", "sys_category_record_mm",
						   "sys_category_record_mm",
						   $sb->expr()->eq("tx_jobs_domain_model_posting.uid", "sys_category_record_mm.uid_foreign"));
				$sb = buildCategoriesToSQL($categories, $sb);
				$result = $sb->execute()->fetchAll(\Doctrine\DBAL\FetchMode::COLUMN);
				$qb->select("*")
				   ->from("tx_jobs_domain_model_location")
				   ->where($qb->expr()->in("uid", $result));
			}

			$query->statement($qb->getSQL());

			return $query->execute();
		}
	}
