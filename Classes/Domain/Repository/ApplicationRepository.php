<?php

	namespace ITX\Jobapplications\Domain\Repository;

	use TYPO3\CMS\Extbase\Persistence\QueryInterface;
    use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

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
	 * The repository for Applications
	 */
	class ApplicationRepository extends JobapplicationsRepository
	{

		/**
		 * Function for filtering applications
		 *
		 * @param int|null $contact
		 * @param int|null $posting
		 * @param int|null $status
		 * @param int      $archived
		 * @param string   $orderBy
		 * @param string   $order
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findByFilter(?int   $contact, ?int $posting, ?int $status, int $archived = 0,
									 string $orderBy = "crdate",
									 string $order = QueryInterface::ORDER_ASCENDING)
		{
			$query = $this->createQuery();
			$query->getQuerySettings()->setRespectStoragePage(false)
				  ->setIgnoreEnableFields(true);

			$andArray = [];

			$andArray[] = $query->equals("archived", $archived);

			if ($contact)
			{
				$andArray[] = $query->equals("posting.contact.uid", $contact);
			}

			if ($posting != 0)
			{
				if ($posting === -1)
				{
					$andArray[] = $query->equals("posting", 0);
				}
				else
				{
					$andArray[] = $query->equals("posting.uid", $posting);
				}
			}

			if ($status)
			{
				$andArray[] = $query->equals("status.uid", $status);
			}

			$query->matching(
				$query->logicalAnd(...$andArray)
			);

			$query->setOrderings([$orderBy => $order]);

			return $query->execute();
		}

		/**
		 * Returns all objects of this repository.
		 *
		 * @return QueryResultInterface|array
		 */
		public function findAll()
		{
			$query = $this->createQuery();

            $query->getQuerySettings()
                  ->setRespectStoragePage(false)
                  ->setIgnoreEnableFields(true);
			$query->setOrderings([
									 "crdate" => QueryInterface::ORDER_DESCENDING
								 ]);

			return $query->execute();
		}

		/**
		 * @param int $contact
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findNewApplicationsByContact(int $contact)
		{
			$query = $this->createQuery();
			$query->getQuerySettings()
				  ->setRespectStoragePage(false)
				  ->setIgnoreEnableFields(true);
			$query->matching(
				$query->logicalAnd($query->equals('posting.contact.uid', $contact),
                                   $query->equals('status.is_new_status', 1),
                                   $query->equals('archived', 0)

                )
			);

			return $query->execute();
		}

		/**
		 * Finds applications which are older than or equal the given timestamp
		 *
		 * @param      $timestamp int
		 * @param      $status    bool
		 * @param bool $notAnonymized
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
		 */
		public function findOlderThan(int $timestamp, bool $status = false, bool $notAnonymized = false)
		{
			$query = $this->createQuery();
			$query->getQuerySettings()->setRespectStoragePage(false)->setIgnoreEnableFields(true);

			$andArray = [];
			$andArray[] = $query->lessThanOrEqual("crdate", $timestamp);

			if ($notAnonymized)
			{
				$andArray[] = $query->equals("anonymized", 0);
			}

			if ($status)
			{
				$andArray[] = $query->equals("status.isEndStatus", 1);
			}

			$query->matching(
				$query->logicalAnd(
					...$andArray
				)
			);

			return $query->execute();
		}

		/**
		 * Finds applications which are older than or equal the given timestamp and which are not anonymized
		 *
		 * @param $timestamp int
		 * @param $status    bool
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
		 */
		public function findNotAnonymizedOlderThan(int $timestamp, bool $status = false)
		{
			$query = $this->createQuery();

			$query->getQuerySettings()->setRespectStoragePage(false)->setIgnoreEnableFields(true);

			$andArray = [];
			$andArray[] = $query->lessThanOrEqual("crdate", $timestamp);
			$andArray[] = $query->logicalNot($query->equals("last_name", $timestamp));

			if ($status)
			{
				$andArray[] = $query->equals("status.isEndStatus", 1);
			}

			$query->matching(
				$query->logicalAnd(
					$andArray
				)
			);

			return $query->execute();
		}

		/**
		 * @param int $uid
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findByPostingIncludingHiddenAndDeleted(int $uid)
		{
			$query = $this->createQuery();
			$query->getQuerySettings()
				  ->setRespectStoragePage(false)
				  ->setIgnoreEnableFields(true);
			$query->matching(
				$query->logicalAnd($query->equals('posting.uid', $uid))
			);

			return $query->execute();
		}
	}
