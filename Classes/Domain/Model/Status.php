<?php

	namespace ITX\Jobapplications\Domain\Model;

	use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
	use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

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
	 * Status
	 */
	class Status extends AbstractEntity
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
		 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\ITX\Jobapplications\Domain\Model\Status>
		 */
		protected $followers = null;

		/**
		 * isEndStatus
		 *
		 * @var bool
		 */
		protected $isEndStatus = false;

		/**
		 * isEndStatus
		 *
		 * @var bool
		 */
		protected $isNewStatus = false;

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
		public function setFollowers(ObjectStorage $followers)
		{
			$this->followers = $followers;
		}

		/**
		 * @return bool
		 */
		public function getIsEndStatus(): bool
		{
			return $this->isEndStatus;
		}

		/**
		 * @param bool $isEndStatus
		 */
		public function setIsEndStatus(bool $isEndStatus): void
		{
			$this->isEndStatus = $isEndStatus;
		}

		/**
		 * @return bool
		 */
		public function isNewStatus(): bool
		{
			return $this->isNewStatus;
		}

		/**
		 * @param bool $isNewStatus
		 */
		public function setIsNewStatus(bool $isNewStatus): void
		{
			$this->isNewStatus = $isNewStatus;
		}
	}
