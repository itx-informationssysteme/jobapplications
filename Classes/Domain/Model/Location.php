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
	 * Location
	 */
	class Location extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
	{

		/**
		 * name
		 *
		 * @var string
		 */
		protected $name = '';

		/**
		 * addressStreetAndNumber
		 *
		 * @var string
		 */
		protected $addressStreetAndNumber = '';

		/**
		 * addressAddition
		 *
		 * @var string
		 */
		protected $addressAddition = '';

		/**
		 * addressPostCode
		 *
		 * @var string
		 */
		protected $addressPostCode = '';

		/**
		 * addressCity
		 *
		 * @var string
		 */
		protected $addressCity = '';

		/**
		 * addressCountry
		 *
		 * @var string
		 */
		protected $addressCountry = '';

		/**
		 * latitude
		 *
		 * @var string
		 */
		protected $latitude = '';

		/**
		 * londitude
		 *
		 * @var string
		 */
		protected $londitude = '';

		/**
		 * Returns the name
		 *
		 * @return string name
		 */
		public function getName()
		{
			return $this->name;
		}

		/**
		 * @return string
		 */
		public function getAddressStreetAndNumber(): string
		{
			return $this->addressStreetAndNumber;
		}

		/**
		 * @param string $addressStreetAndNumber
		 */
		public function setAddressStreetAndNumber(string $addressStreetAndNumber): void
		{
			$this->addressStreetAndNumber = $addressStreetAndNumber;
		}

		/**
		 * @return string
		 */
		public function getAddressAddition(): string
		{
			return $this->addressAddition;
		}

		/**
		 * @param string $addressAddition
		 */
		public function setAddressAddition(string $addressAddition): void
		{
			$this->addressAddition = $addressAddition;
		}

		/**
		 * @return string
		 */
		public function getAddressPostCode(): string
		{
			return $this->addressPostCode;
		}

		/**
		 * @param string $addressPostCode
		 */
		public function setAddressPostCode(string $addressPostCode): void
		{
			$this->addressPostCode = $addressPostCode;
		}

		/**
		 * @return string
		 */
		public function getAddressCity(): string
		{
			return $this->addressCity;
		}

		/**
		 * @param string $addressCity
		 */
		public function setAddressCity(string $addressCity): void
		{
			$this->addressCity = $addressCity;
		}

		/**
		 * @return string
		 */
		public function getAddressCountry(): string
		{
			return $this->addressCountry;
		}

		/**
		 * @param string $addressCountry
		 */
		public function setAddressCountry(string $addressCountry): void
		{
			$this->addressCountry = $addressCountry;
		}

		/**
		 * Sets the address
		 *
		 * @param string $address
		 *
		 * @return void
		 */
		public function setAddress($address)
		{
			$this->address = $address;
		}

		/**
		 * Returns the latitude
		 *
		 * @return string $latitude
		 */
		public function getLatitude()
		{
			return $this->latitude;
		}

		/**
		 * Sets the latitude
		 *
		 * @param string $latitude
		 *
		 * @return void
		 */
		public function setLatitude($latitude)
		{
			$this->latitude = $latitude;
		}

		/**
		 * Returns the londitude
		 *
		 * @return string $londitude
		 */
		public function getLonditude()
		{
			return $this->londitude;
		}

		/**
		 * Sets the londitude
		 *
		 * @param string $londitude
		 *
		 * @return void
		 */
		public function setLonditude($londitude)
		{
			$this->londitude = $londitude;
		}
	}
