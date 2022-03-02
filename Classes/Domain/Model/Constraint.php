<?php

	namespace ITX\Jobapplications\Domain\Model;

	/**
	 * Class Constraint
	 *
	 * @package ITX\Jobapplications\Domain\Model
	 */
	class Constraint
	{
		/** @var array
		 */
		protected $division = [];

		/** @var array
		 */
		protected $careerLevel = [];

		/** @var array
		 */
		protected $employmentType = [];

		/** @var array
		 */
		protected $location = [];

		/**
		 * @return array
		 */
		public function getDivision(): array
		{
			return $this->division;
		}

		/**
		 * @param array $division
		 */
		public function setDivision(array $division): void
		{
			$this->division = $division;
		}

		/**
		 * @return array
		 */
		public function getCareerLevel(): array
		{
			return $this->careerLevel;
		}

		/**
		 * @param array $careerLevel
		 */
		public function setCareerLevel(array $careerLevel): void
		{
			$this->careerLevel = $careerLevel;
		}

		/**
		 * @return array
		 */
		public function getEmploymentType(): array
		{
			return $this->employmentType;
		}

		/**
		 * @param array $employmentType
		 */
		public function setEmploymentType(array $employmentType): void
		{
			$this->employmentType = $employmentType;
		}

		/**
		 * @return array
		 */
		public function getLocation(): array
		{
			return $this->location;
		}

		/**
		 * @param array $location
		 */
		public function setLocation(array $location): void
		{
			$this->location = $location;
		}
	}