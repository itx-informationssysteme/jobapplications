<?php

	namespace ITX\Jobapplications\Domain\Repository;

	use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

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
	 * The repository for Locations
	 */
	class LocationRepository extends \ITX\Jobapplications\Domain\Repository\JobapplicationsRepository
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
			$qb = parent::getQueryBuilder("tx_jobapplications_domain_model_location");
			$query = $this->createQuery();

			if (count($categories) == 0)
			{
				$qb->select("*")
				   ->from("tx_jobapplications_domain_model_location");
			}
			else
			{
				$sb = parent::getQueryBuilder("tx_jobapplications_domain_model_posting");
				$sb
					->select("location")
					->from("tx_jobapplications_domain_model_posting")
					->join("tx_jobapplications_domain_model_posting", "sys_category_record_mm",
						   "sys_category_record_mm",
						   $sb->expr()->eq("tx_jobapplications_domain_model_posting.uid", "sys_category_record_mm.uid_foreign"));
				$sb = parent::buildCategoriesToSQL($categories, $sb);
				$result = $sb->execute()->fetchAll(\Doctrine\DBAL\FetchMode::COLUMN);
				$qb->select("*")
				   ->from("tx_jobapplications_domain_model_location")
				   ->where($qb->expr()->in("uid", $result));
			}

			$query->statement($qb->getSQL());

			return $query->execute();
		}
	}
