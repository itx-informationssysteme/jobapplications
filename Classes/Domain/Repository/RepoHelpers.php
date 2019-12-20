<?php

	use TYPO3\CMS\Core\Database\ConnectionPool;
	use TYPO3\CMS\Core\Utility\GeneralUtility;

	/**
	 * Helper function for building the sql for categories
	 *
	 * @param $categories array Array of category uids
	 *
	 * @return \TYPO3\CMS\Core\Database\Query\QueryBuilder
	 */

	function buildCategoriesToSQL(array $categories, \TYPO3\CMS\Core\Database\Query\QueryBuilder $qb)
	{
		$statement = "";
		for ($i = 0; $i < count($categories); $i++)
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
	function getQueryBuilder(string $table)
	{
		return GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
	}
