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
			$orArray = [];

			for ($i = 0; $i < count($uids); $i++)
			{
				$orArray[] = $query->equals("uid", $uids[$i]);
			}

			$query->matching(
				$query->logicalOr($orArray)
			);

			return $query->execute();
		}

		/**
		 * Returns all objects of this repository.
		 *
		 * @param $orderBy string orderBy fieldname to order by
		 * @param $order   string order SQL ASC or DESC
		 *
		 * @return QueryResultInterface|array
		 */
		public function findAllWithOrder(string $orderBy, string $order)
		{
			$query = $this->createQuery();
			$query->getQuerySettings()->setRespectStoragePage(false);
			$query->setOrderings([
									 $orderBy => $order
								 ]);

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
			$query->getQuerySettings()->setRespectStoragePage(false);
			$query->matching(
				$query->equals("be_user", $beUserUid)
			);
			$result = $query->execute();

			if (count($result->toArray()) > 1)
			{
				$result = $result[0];
			}

			return $result;
		}
	}
