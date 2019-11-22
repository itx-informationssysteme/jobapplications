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
	 * The repository for Applications
	 */
	class StatusRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
	{
		/**
		 * Finds all followers of given status
		 *
		 * @param $status
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findFollowers($status)
		{
			$query = $this->createQuery();
			$query->statement("SELECT * FROM tx_jobs_domain_model_status
    									JOIN tx_jobs_domain_model_status_mm
    									ON tx_jobs_domain_model_status.uid = tx_jobs_domain_model_status_mm.uid_foreign
										WHERE uid_local = ".$status." ORDER BY name");

			return $query->execute();
		}
	}

