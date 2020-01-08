<?php

	namespace ITX\Jobs\Domain\Repository;

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
	 * The repository for Contacts
	 */
	class ContactRepository extends \ITX\Jobs\Domain\Repository\JobsRepository
	{
		/**
		 * @param array $uids
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
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
