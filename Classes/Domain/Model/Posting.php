<?php

	namespace ITX\Jobapplications\Domain\Model;

	use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
	use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
	use TYPO3\CMS\Extbase\Domain\Model\FileReference;
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
	 * A Job Posting has a location and  a contact person.
	 */
	class Posting extends AbstractEntity
	{
		/** @var boolean */
		protected $hidden;

		/** @var boolean */
		protected $deleted;

		/** @var \DateTime */
		protected $starttime;

		/** @var \DateTime */
		protected $endtime;

		/**
		 * title
		 *
		 * @var string
		 */
		protected $title = '';

		/**
		 * datePosted
		 *
		 * @var \DateTime
		 */
		protected $datePosted = null;

		/**
		 * careerLevel
		 *
		 * @var string
		 */
		protected $careerLevel = '';

		/**
		 * division
		 *
		 * @var string
		 */
		protected $division = '';

		/**
		 * employmentType
		 *
		 * @var string
		 */
		protected $employmentType = '';

		/**
		 * termsOfEmployment
		 *
		 * @var string
		 */
		protected $termsOfEmployment = '';

		/**
		 * companyDescription
		 *
		 * @var string
		 */
		protected $companyDescription = '';

		/**
		 * jobDescription
		 *
		 * @var string
		 */
		protected $jobDescription = '';

		/**
		 * roleDescription
		 *
		 * @var string
		 */
		protected $roleDescription = '';

		/**
		 * skillRequirements
		 *
		 * @var string
		 */
		protected $skillRequirements = '';

		/**
		 * benefits
		 *
		 * @var string
		 */
		protected $benefits = '';

		/**
		 * baseSalary
		 *
		 * @var string
		 */
		protected $baseSalary = 0;

		/**
		 * requiredDocuments
		 *
		 * @var string
		 */
		protected $requiredDocuments = '';

		/**
		 * companyInformation
		 *
		 * @var string
		 */
		protected $companyInformation = '';

		/**
		 * locations
		 *
		 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\ITX\Jobapplications\Domain\Model\Location>
		 */
		protected $locations = null;

		/**
		 * contact
		 *
		 * @var \ITX\Jobapplications\Domain\Model\Contact
		 */
		protected $contact = null;

		/**
		 * detailViewImage
		 *
		 * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
		 * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
		 */
		protected $detailViewImage = null;

		/**
		 * listViewImage
		 *
		 * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
		 * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
		 */
		protected $listViewImage = null;

		/**
		 * categories
		 *
		 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
		 * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
		 */
		protected $categories = null;


		/**
		 * region
		 *
		 * @var string
		 */
		protected $regions = '';

		/**
		 * __construct
		 */
		public function __construct()
		{

			//Do not remove the next line: It would break the functionality
			$this->initStorageObjects();
		}

		/**
		 * Initializes all ObjectStorage properties
		 * Do not modify this method!
		 * It will be rewritten on each save in the extension builder
		 * You may modify the constructor of this class instead
		 *
		 * @return void
		 */
		protected function initStorageObjects()
		{
			$this->categories = new ObjectStorage();
		}

		/**
		 * Returns the title
		 *
		 * @return string title
		 */
		public function getTitle()
		{
			return $this->title;
		}

		/**
		 * Sets the title
		 *
		 * @param string $title
		 *
		 * @return void
		 */
		public function setTitle($title)
		{
			$this->title = $title;
		}

		/**
		 * Returns the datePosted
		 *
		 * @return \DateTime datePosted
		 */
		public function getDatePosted()
		{
			return $this->datePosted;
		}

		/**
		 * Sets the datePosted
		 *
		 * @param \DateTime $datePosted
		 *
		 * @return void
		 */
		public function setDatePosted(\DateTime $datePosted)
		{
			$this->datePosted = $datePosted;
		}

		/**
		 * Returns the careerLevel
		 *
		 * @return string careerLevel
		 */
		public function getCareerLevel()
		{
			return $this->careerLevel;
		}

		/**
		 * Sets the careerLevel
		 *
		 * @param string $careerLevel
		 *
		 * @return void
		 */
		public function setCareerLevel($careerLevel)
		{
			$this->careerLevel = $careerLevel;
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
		 * Returns the employmentType
		 *
		 * @return string employmentType
		 */
		public function getEmploymentType()
		{
			return $this->employmentType;
		}

		/**
		 * Sets the employmentType
		 *
		 * @param string $employmentType
		 *
		 * @return void
		 */
		public function setEmploymentType($employmentType)
		{
			$this->employmentType = $employmentType;
		}

		/**
		 * @return string[]
		 */
		public function getDeserializedEmploymentTypes()
		{
			return explode(',', $this->employmentType);
		}

		/**
		 * Returns the termsOfEmployment
		 *
		 * @return string termsOfEmployment
		 */
		public function getTermsOfEmployment()
		{
			return $this->termsOfEmployment;
		}

		/**
		 * Sets the termsOfEmployment
		 *
		 * @param string $termsOfEmployment
		 *
		 * @return void
		 */
		public function setTermsOfEmployment($termsOfEmployment)
		{
			$this->termsOfEmployment = $termsOfEmployment;
		}

		/**
		 * Returns the companyDescription
		 *
		 * @return string companyDescription
		 */
		public function getCompanyDescription()
		{
			return $this->companyDescription;
		}

		/**
		 * Sets the companyDescription
		 *
		 * @param string $companyDescription
		 *
		 * @return void
		 */
		public function setCompanyDescription($companyDescription)
		{
			$this->companyDescription = $companyDescription;
		}

		/**
		 * Returns the jobDescription
		 *
		 * @return string jobDescription
		 */
		public function getJobDescription()
		{
			return $this->jobDescription;
		}

		/**
		 * Sets the jobDescription
		 *
		 * @param string $jobDescription
		 *
		 * @return void
		 */
		public function setJobDescription($jobDescription)
		{
			$this->jobDescription = $jobDescription;
		}

		/**
		 * Returns the roleDescription
		 *
		 * @return string roleDescription
		 */
		public function getRoleDescription()
		{
			return $this->roleDescription;
		}

		/**
		 * Sets the roleDescription
		 *
		 * @param string $roleDescription
		 *
		 * @return void
		 */
		public function setRoleDescription($roleDescription)
		{
			$this->roleDescription = $roleDescription;
		}

		/**
		 * Returns the skillRequirements
		 *
		 * @return string skillRequirements
		 */
		public function getSkillRequirements()
		{
			return $this->skillRequirements;
		}

		/**
		 * Sets the skillRequirements
		 *
		 * @param string $skillRequirements
		 *
		 * @return void
		 */
		public function setSkillRequirements($skillRequirements)
		{
			$this->skillRequirements = $skillRequirements;
		}

		/**
		 * Returns the benefits
		 *
		 * @return string benefits
		 */
		public function getBenefits()
		{
			return $this->benefits;
		}

		/**
		 * Sets the benefits
		 *
		 * @param string $benefits
		 *
		 * @return void
		 */
		public function setBenefits($benefits)
		{
			$this->benefits = $benefits;
		}

		/**
		 * Returns the baseSalary
		 *
		 * @return string baseSalary
		 */
		public function getBaseSalary()
		{
			return $this->baseSalary;
		}

		/**
		 * Sets the baseSalary
		 *
		 * @param int $baseSalary
		 *
		 * @return void
		 */
		public function setBaseSalary($baseSalary)
		{
			$this->baseSalary = $baseSalary;
		}

		/**
		 * Returns the requiredDocuments
		 *
		 * @return string requiredDocuments
		 */
		public function getRequiredDocuments()
		{
			return $this->requiredDocuments;
		}

		/**
		 * Sets the requiredDocuments
		 *
		 * @param string $requiredDocuments
		 *
		 * @return void
		 */
		public function setRequiredDocuments($requiredDocuments)
		{
			$this->requiredDocuments = $requiredDocuments;
		}

		/**
		 * Returns the companyInformation
		 *
		 * @return string companyInformation
		 */
		public function getCompanyInformation()
		{
			return $this->companyInformation;
		}

		/**
		 * Sets the companyInformation
		 *
		 * @param string $companyInformation
		 *
		 * @return void
		 */
		public function setCompanyInformation($companyInformation)
		{
			$this->companyInformation = $companyInformation;
		}

		/**
		 * Returns the locations
		 *
		 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\ITX\Jobapplications\Domain\Model\Location> $locations
		 */
		public function getLocations()
		{
			return $this->locations;
		}

		/**
		 * Sets the location
		 *
		 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\ITX\Jobapplications\Domain\Model\Location> $locations
		 *
		 * @return void
		 */
		public function setLocations(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $locations)
		{
			$this->locations = $locations;
		}

		/**
		 * Returns the contact
		 *
		 * @return \ITX\Jobapplications\Domain\Model\Contact $contact
		 */
		public function getContact()
		{
			return $this->contact;
		}

		/**
		 * Sets the contact
		 *
		 * @param \ITX\Jobapplications\Domain\Model\Contact $contact
		 *
		 * @return void
		 */
		public function setContact(Contact $contact)
		{
			$this->contact = $contact;
		}

		/**
		 * Returns the detailViewImage
		 *
		 * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $detailViewImage
		 */
		public function getDetailViewImage()
		{
			return $this->detailViewImage;
		}

		/**
		 * Sets the detailViewImage
		 *
		 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $detailViewImage
		 *
		 * @return void
		 */
		public function setDetailViewImage(FileReference $detailViewImage)
		{
			$this->detailViewImage = $detailViewImage;
		}

		/**
		 * Returns the listViewImage
		 *
		 * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $listViewImage
		 */
		public function getListViewImage()
		{
			return $this->listViewImage;
		}

		/**
		 * Sets the listViewImage
		 *
		 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $listViewImage
		 *
		 * @return void
		 */
		public function setListViewImage(FileReference $listViewImage)
		{
			$this->listViewImage = $listViewImage;
		}

		/**
		 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
		 */
		public function getCategories()
		{
			return $this->categories;
		}

		/**
		 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category> $categories
		 */
		public function setCategories(ObjectStorage $categories): void
		{
			$this->categories = $categories;
		}

		/**
		 * @return bool
		 */
		public function isHidden(): bool
		{
			return $this->hidden;
		}

		/**
		 * @param bool $hidden
		 */
		public function setHidden(bool $hidden): void
		{
			$this->hidden = $hidden;
		}

		/**
		 * @return bool
		 */
		public function isDeleted()
		{
			return $this->deleted;
		}

		/**
		 * @param bool $deleted
		 */
		public function setDeleted(bool $deleted): void
		{
			$this->deleted = $deleted;
		}

		/**
		 * @return \DateTime
		 */
		public function getStarttime()
		{
			return $this->starttime;
		}

		/**
		 * @param \DateTime $starttime
		 */
		public function setStarttime($starttime)
		{
			$this->starttime = $starttime;
		}

		/**
		 * @return \DateTime
		 */
		public function getEndtime()
		{
			return $this->endtime;
		}

		/**
		 * @param \DateTime $endtime
		 */
		public function setEndtime($endtime)
		{
			$this->endtime = $endtime;
		}

		/**
		 * @return region
		 */
		public function getRegions():string
		{
			return $this->regions;
		}

		/**
		 * @param $regions
		 *
		 * @return void
		 */
		public  function setRegions(string $regions)
		{
			$this->regions = $regions;
		}
	}
