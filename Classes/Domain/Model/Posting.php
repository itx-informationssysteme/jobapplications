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
     * applications
     * 
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\ITX\Jobs\Domain\Model\Application>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $applications = null;

    /**
     * contact
     * 
     * @var \ITX\Jobs\Domain\Model\Contact
     */
    protected $contact = null;

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
        $this->applications = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
     * @return void
     */
    public function setLocation(\ITX\Jobs\Domain\Model\Location $location)
    {
        $this->location = $location;
    }

    /**
     * Adds a Application
     * 
     * @param \ITX\Jobs\Domain\Model\Application $application
     * @return void
     */
    public function addApplication(\ITX\Jobs\Domain\Model\Application $application)
    {
        $this->applications->attach($application);
    }

    /**
     * Removes a Application
     * 
     * @param \ITX\Jobs\Domain\Model\Application $applicationToRemove The Application to be removed
     * @return void
     */
    public function removeApplication(\ITX\Jobs\Domain\Model\Application $applicationToRemove)
    {
        $this->applications->detach($applicationToRemove);
    }

    /**
     * Returns the applications
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\ITX\Jobs\Domain\Model\Application> $applications
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * Sets the applications
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\ITX\Jobs\Domain\Model\Application> $applications
     * @return void
     */
    public function setApplications(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $applications)
    {
        $this->applications = $applications;
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
     * @return void
     */
    public function setContact(\ITX\Jobs\Domain\Model\Contact $contact)
    {
        $this->contact = $contact;
    }
}
