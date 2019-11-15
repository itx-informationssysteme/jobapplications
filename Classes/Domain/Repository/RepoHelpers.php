<?php

	/**
	 * Helper function for building the sql for categories
	 *
	 * @param $categories array Array of category uids
	 *
	 * @return string An SQL Statement which can be concatenated to a WHERE call
	 */

	function buildCategoriesToSQL($categories)
	{
		$statement = "";
		for ($i = 0; $i < count($categories); $i++)
		{
			if ($i == 0)
			{
				if (count($categories) > 1)
				{
					$statement .= "AND (sys_category_record_mm.uid_local = ".$categories[$i]." ";
				}
				else
				{
					$statement .= "AND sys_category_record_mm.uid_local = ".$categories[$i]." ";
				}
			}
			else
			{
				$statement .= "OR sys_category_record_mm.uid_local = ".$categories[$i]." ";
				if ($i == count($categories) - 1)
				{
					$statement .= ")";
				}
			}
		}

		return $statement;
	}
