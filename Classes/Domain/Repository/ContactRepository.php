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
		public function findMultipleByUid(array $uids)
		{
			$query = $this->createQuery();

			$statement = "";
			for ($i = 0; $i < count($uids); $i++)
			{
				if ($i == 0)
				{
					if (count($uids) > 1)
					{
						$statement .= "WHERE (uid = ".$uids[$i]." ";
					}
					else
					{
						$statement .= "WHERE uid = ".$uids[$i]." ";
					}
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

		/**
		 * Returns all objects of this repository.
		 *
		 * @param $orderBy string orderBy fieldname to order by
		 * @param $order string order SQL ASC or DESC
		 *
		 * @return QueryResultInterface|array
		 */
		public function findAllWithOrder(string $orderBy, string $order)
		{
			$query = $this->createQuery();
			$query->statement("SELECT * FROM tx_jobs_domain_model_contact WHERE hidden = 0 AND deleted = 0 ORDER BY ".$orderBy." ".$order);

			return $query->execute();
		}

		/**
		 * @param $beUserUid int
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findByBackendUser(int $beUserUid)
		{
			$query = $this->createQuery();
			$query->statement("SELECT DISTINCT * FROM tx_jobs_domain_model_contact WHERE hidden = 0 AND deleted = 0 AND be_user = ".$beUserUid);

			return $query->execute();
		}
	}
