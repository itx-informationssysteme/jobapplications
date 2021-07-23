<?php

	namespace ITX\Jobapplications\Domain\Repository;

	use TYPO3\CMS\Extbase\Persistence\QueryInterface;

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
		 * @param array|null $categories array
		 * @param string     $orderBy
		 * @param string     $order
		 *
		 * @return QueryResultInterface|array
		 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
		 */
		public function findAll(array $categories = null, string $orderBy = "name", string $order = QueryInterface::ORDER_ASCENDING)
		{
			$query = $this->createQuery();

			$andConstraints = [];

			if (!empty($categories))
			{
				$andConstraints[] = $query->contains('categories', $categories);
			}

			if (!empty($andConstraints))
			{
				$query->matching(
					$query->logicalAnd($andConstraints)
				);
			}

			$query->setOrderings([$orderBy => $order]);

			return $query->execute();
		}
	}
