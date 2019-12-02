<?php

	namespace ITX\Jobs\Domain\Model;

	use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
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
	 * A Job Posting has a location and  a contact person.
	 */
	class Posting extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
	{

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
		 * validThrough
		 *
		 * @var \DateTime
		 */
		protected $validThrough = null;

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
		 * location
		 *
		 * @var \ITX\Jobs\Domain\Model\Location
		 */
		protected $location = null;

		/**
		 * contact
		 *
		 * @var \ITX\Jobs\Domain\Model\Contact
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
		 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
		 */
		protected $categories;

		/**
		 * @return ObjectStorage
		 */
		public function getCategories(): ObjectStorage
		{
			return $this->categories;
		}

		/**
		 * @param ObjectStorage $categories
		 */
		public function setCategories(ObjectStorage $categories)
		{
			$this->categories = $categories;
		}

		/**
		 * __construct
		 */
		public function __construct()
		{

			//Do not remove the next line: It would break the functionality
			$this->initStorageObjects();
			$this->categories = new ObjectStorage();
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
		 * Returns the validThrough
		 *
		 * @return \DateTime validThrough
		 */
		public function getValidThrough()
		{
			return $this->validThrough;
		}

		/**
		 * Sets the validThrough
		 *
		 * @param \DateTime $validThrough
		 *
		 * @return void
		 */
		public function setValidThrough(\DateTime $validThrough)
		{
			$this->validThrough = $validThrough;
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
		 * Returns the location
		 *
		 * @return \ITX\Jobs\Domain\Model\Location location
		 */
		public function getLocation()
		{
			return $this->location;
		}

		/**
		 * Sets the location
		 *
		 * @param \ITX\Jobs\Domain\Model\Location $location
		 *
		 * @return void
		 */
		public function setLocation(\ITX\Jobs\Domain\Model\Location $location)
		{
			$this->location = $location;
		}

		/**
		 * Returns the contact
		 *
		 * @return \ITX\Jobs\Domain\Model\Contact $contact
		 */
		public function getContact()
		{
			return $this->contact;
		}

		/**
		 * Sets the contact
		 *
		 * @param \ITX\Jobs\Domain\Model\Contact $contact
		 *
		 * @return void
		 */
		public function setContact(\ITX\Jobs\Domain\Model\Contact $contact)
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
		public function setDetailViewImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $detailViewImage)
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
		public function setListViewImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $listViewImage)
		{
			$this->listViewImage = $listViewImage;
		}

		/**
		 * @return boolean
		 */
		public function getIsValid()
		{
			$current = new \DateTime();
			$validThrougDate = $this->validThrough;

			return ($this->datePosted <= $current) && ($validThrougDate == null || ($validThrougDate->modify("+1 day") >= $current));
		}
	}
