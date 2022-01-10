<?php
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

	namespace ITX\Jobapplications\Domain\Repository;

	use TYPO3\CMS\Extbase\Persistence\Repository;
	use TYPO3\CMS\Core\Database\Query\QueryBuilder;
	use TYPO3\CMS\Core\Database\ConnectionPool;
	use TYPO3\CMS\Core\Utility\GeneralUtility;

	/**
	 * Class JobapplicationsRepository
	 *
	 * Parent Repository for other repos
	 */
	abstract class JobapplicationsRepository extends Repository
	{
		/**
		 * Helper function for building the sql for categories
		 *
		 * @param array                                       $categories Array of category uids
		 * @param \TYPO3\CMS\Core\Database\Query\QueryBuilder $qb         Query Builder to add to
		 *
		 * @return \TYPO3\CMS\Core\Database\Query\QueryBuilder
		 */

		public function buildCategoriesToSQL(array $categories, QueryBuilder $qb)
		{
			$statement = "";
			for ($i = 0, $iMax = count($categories); $i < $iMax; $i++)
			{
				if ($i == 0)
				{
					if (count($categories) > 1)
					{
						$qb->andWhere($qb->expr()->eq("sys_category_record_mm.uid_local", $categories[$i]));

						$statement .= "AND (sys_category_record_mm.uid_local = ".$categories[$i]." ";
					}
					else
					{
						$qb->andWhere($qb->expr()->eq("sys_category_record_mm.uid_local", $categories[$i]));

						$statement .= "AND sys_category_record_mm.uid_local = ".$categories[$i]." ";
					}
				}
				else
				{
					$qb->orWhere($qb->expr()->eq("sys_category_record_mm.uid_local", $categories[$i]));
					$statement .= "OR sys_category_record_mm.uid_local = ".$categories[$i]." ";
					if ($i == count($categories) - 1)
					{
						$statement .= ")";
					}
				}
			}

			return $qb;
		}

		/**
		 * @param string $table
		 *
		 * @return \TYPO3\CMS\Core\Database\Query\QueryBuilder
		 */
		public function getQueryBuilder(string $table)
		{
			return GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
		}
	}