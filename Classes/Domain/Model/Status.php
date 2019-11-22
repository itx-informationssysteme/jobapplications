<?php

	namespace ITX\Jobs\Domain\Model;

	use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

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
	 * Status
	 */
	class Status extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
	{
		/**
		 * name
		 *
		 * @var string
		 */
		protected $name = "";

		/**
		 * followers
		 *
		 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\ITX\Jobs\Domain\Model\Status>
		 */
		protected $followers = null;

		/**
		 * @return string
		 */
		public function getName()
		{
			return $this->name;
		}

		/**
		 * @param string $name
		 */
		public function setName(string $name): void
		{
			$this->name = $name;
		}

		/**
		 * @return ObjectStorage
		 */
		public function getFollowers()
		{
			return $this->followers;
		}

		/**
		 * @param ObjectStorage $followers
		 */
		public function setFollowers(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $followers)
		{
			$this->followers = $followers;
		}

	}
