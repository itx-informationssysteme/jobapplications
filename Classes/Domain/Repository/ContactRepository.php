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
 * The repository for Contacts
 */
class ContactRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
	/**
	 * @param $uids array
	 */
	public function findMultipleByUid($uids) {
		$query = $this->createQuery();


		$statement = "";
		for ($i = 0; $i < count($uids); $i++)
		{
			if ($i == 0)
			{
				$statement .= "WHERE (uid = ".$uids[$i]." ";
			}
			else
			{
				$statement .= "OR uid = ".$uids[$i]." ";
				if ($i == count($uids) - 1)
				{
					$statement .= ")";
				}
			}
		}

		$query->statement("SELECT * FROM tx_jobs_domain_model_contact ".$statement);

		return $query->execute();
	}
}
