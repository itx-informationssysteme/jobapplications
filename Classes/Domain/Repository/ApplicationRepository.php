<?php

	namespace ITX\Jobs\Domain\Repository;

	use ITX\Jobs\Domain\Model\Contact;
	use ITX\Jobs\Domain\Model\Posting;

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
		 *
		 * @param $contactUid int
		 * @param $postingUid int
		 */
		public function findByFilter($contact, $posting, $archived=0, $orderBy="crdate", $order="ASC")
		{
			$contactSQL = "";
			$postingSQL = "";

			$baseSQL = "SELECT * FROM tx_jobs_domain_model_application WHERE deleted = 0 AND archived = ".$archived." ";

			if ($contact)
			{
				$contactSQL = "AND posting IN( SELECT uid FROM tx_jobs_domain_model_posting 
								WHERE deleted = 0 AND contact = ".$contact.")";
			}
			if ($posting)
			{
				$postingSQL = "AND posting = \"$posting\"";
			}

			$query = $this->createQuery();

			$query->statement(
				$baseSQL." ".$contactSQL." ".$postingSQL." ORDER BY ".$orderBy." ".$order
			);

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

			$query->statement("SELECT * FROM tx_jobs_domain_model_application 
										WHERE deleted = 0 AND hidden = 0 AND archived = 0 ORDER BY crdate DESC");

			return $query->execute();
		}

		/**
		 * @param int $contact
		 *
		 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
		 */
		public function findNewApplicationsByContact($contact)
		{
			$query = $this->createQuery();

			$query->statement("SELECT * FROM tx_jobs_domain_model_application 
										WHERE deleted = 0 AND hidden = 0 AND archived = 0 AND status = 1 AND posting 
										IN( SELECT uid FROM tx_jobs_domain_model_posting 
										WHERE deleted = 0 AND contact = ".$contact.")");

			return $query->execute();
		}
	}
