<?php

	namespace ITX\Jobs\Domain\Model;

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
		 * @var int
		 */
		protected $addressPostCode = 0;

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
		 * @return int
		 */
		public function getAddressPostCode(): int
		{
			return $this->addressPostCode;
		}

		/**
		 * @param int $addressPostCode
		 */
		public function setAddressPostCode(int $addressPostCode): void
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
