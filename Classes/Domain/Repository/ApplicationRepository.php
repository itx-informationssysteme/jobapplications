<?php

	namespace ITX\Jobs\Domain\Repository;

	use ITX\Jobs\Domain\Model\Contact;
	use ITX\Jobs\Domain\Model\Posting;
	use TYPO3\CMS\Core\Database\ConnectionPool;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

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
	class ApplicationRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
	{

		/**
		 * Function for filtering applications
		 * @param int    $contact
		 * @param int    $posting
		 * @param int    $status
		 * @param int    $archived
		 * @param string $orderBy
		 * @param string $order
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findByFilter(int $contact, int $posting, int $status, int $archived = 0,
									 string $orderBy = "crdate",
									 string $order = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING)
		{
			$query = $this->createQuery();
			$query->getQuerySettings()->setRespectStoragePage(false);

			$andArray = [];

			$andArray[] = $query->equals("archived", $archived);

			if ($contact)
			{
				$andArray[] = $query->equals("posting.contact.uid", $contact);
			}

			if ($posting)
			{
				$andArray[] = $query->equals("posting.uid", $posting);
			}

			if ($status)
			{
				$andArray[] = $query->equals("status.uid", $status);
			}

			$query->matching(
				$query->logicalAnd($andArray)
			);

			$query->setOrderings(array($orderBy => $order));

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
			$query->setOrderings(array(
									 "crdate" => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
								 ));

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
				  ->setRespectStoragePage(false);
			$query->matching(
				$query->logicalAnd([
									   $query->equals('posting.contact.uid', $contact),
									   $query->equals('status.uid', 1),
									   $query->equals('archived', 0)
								   ])
			);

			return $query->execute();
		}

		/**
		 * Finds applications which are older than or equal the given timestamp
		 *
		 * @param $timestamp int
		 * @param $status    bool
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
		 */
		public function findOlderThan(int $timestamp, bool $status = false)
		{
			$query = $this->createQuery();
			$query->getQuerySettings()->setRespectStoragePage(false);

			$andArray = [];
			$andArray[] = $query->lessThanOrEqual("crdate", $timestamp);

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

			$query->getQuerySettings()->setRespectStoragePage(false);

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
	}
