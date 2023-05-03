<?php

	namespace ITX\Jobapplications\Domain\Model;

	use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

	/**
	 * Applicantlocationrequirement
	 */
	class LocationRequirement extends AbstractEntity
	{

		/**
		 * name
		 *
		 * @var string
		 */
		protected $name = '';

		/**
		 * type
		 *
		 * @var string
		 */
		protected $type = '';

		/**
		 * @return string
		 */
		public function getName(): string
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
		 * @return string
		 */
		public function getType(): string
		{
			return $this->type;
		}

		/**
		 * @param string $type
		 */
		public function setType(string $type): void
		{
			$this->type = $type;
		}
	}