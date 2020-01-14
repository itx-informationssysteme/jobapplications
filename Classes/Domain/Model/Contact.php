<?php

	namespace ITX\Jobapplications\Domain\Model;

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
	 * A Contact is the person who handles the application process for this posting.
	 */
	class Contact extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
	{

		/**
		 * firstName
		 *
		 * @var string
		 */
		protected $firstName = '';

		/**
		 * lastName
		 *
		 * @var string
		 */
		protected $lastName = '';

		/**
		 * email
		 *
		 * @var string
		 */
		protected $email = '';

		/**
		 * phone
		 *
		 * @var string
		 */
		protected $phone = '';

		/**
		 * division
		 *
		 * @var string
		 */
		protected $division = '';

		/**
		 * photo
		 *
		 * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
		 * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
		 */
		protected $photo = null;

		/**
		 * photo
		 *
		 * @var \TYPO3\CMS\Extbase\Domain\Model\BackendUser
		 */
		protected $beUser = null;

		/**
		 * @return string
		 */
		public function getFirstName()
		{
			return $this->firstName;
		}

		/**
		 * @param string $firstName
		 */
		public function setFirstName(string $firstName): void
		{
			$this->firstName = $firstName;
		}

		/**
		 * @return string
		 */
		public function getLastName()
		{
			return $this->lastName;
		}

		/**
		 * @param string $lastName
		 */
		public function setLastName(string $lastName): void
		{
			$this->lastName = $lastName;
		}

		/**
		 * Returns the email
		 *
		 * @return string email
		 */
		public function getEmail()
		{
			return $this->email;
		}

		/**
		 * Sets the email
		 *
		 * @param string $email
		 *
		 * @return void
		 */
		public function setEmail($email)
		{
			$this->email = $email;
		}

		/**
		 * Returns the phone
		 *
		 * @return string phone
		 */
		public function getPhone()
		{
			return $this->phone;
		}

		/**
		 * Sets the phone
		 *
		 * @param string $phone
		 *
		 * @return void
		 */
		public function setPhone($phone)
		{
			$this->phone = $phone;
		}

		/**
		 * Returns the division
		 *
		 * @return string division
		 */
		public function getDivision()
		{
			return $this->division;
		}

		/**
		 * Sets the division
		 *
		 * @param string $division
		 *
		 * @return void
		 */
		public function setDivision($division)
		{
			$this->division = $division;
		}

		/**
		 * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
		 */
		public function getPhoto()
		{
			return $this->photo;
		}

		/**
		 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $photo
		 */
		public function setPhoto(\TYPO3\CMS\Extbase\Domain\Model\FileReference $photo)
		{
			$this->photo = $photo;
		}
	}
