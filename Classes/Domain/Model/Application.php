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
 * Application
 */
class Application extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * salutation
     * 
     * @var int
     */
    protected $salutation = 0;

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
     * address
     * 
     * @var string
     */
    protected $address = '';

    /**
     * salaryExpectation
     * 
     * @var string
     */
    protected $salaryExpectation = '';

    /**
     * earliestDateOfJoining
     * 
     * @var \DateTime
     */
    protected $earliestDateOfJoining = null;

    /**
     * cv
     * 
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $cv = null;

    /**
     * coverLetter
     * 
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $coverLetter = null;

    /**
     * testimonials
     * 
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $testimonials = null;

    /**
     * otherFiles
     * 
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $otherFiles = null;

    /**
     * privacyAgreement
     * 
     * @var bool
     */
    protected $privacyAgreement = false;

    /**
     * posting
     * 
     * @var int
     */
    protected $posting = null;

    /**
     * Returns the salutation
     * 
     * @return int salutation
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * Sets the salutation
     * 
     * @param int $salutation
     * @return void
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
    }

    /**
     * Returns the firstName
     * 
     * @return string firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Sets the firstName
     * 
     * @param string $firstName
     * @return void
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Returns the lastName
     * 
     * @return string lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Sets the lastName
     * 
     * @param string $lastName
     * @return void
     */
    public function setLastName($lastName)
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
     * @return void
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Returns the address
     * 
     * @return string address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address
     * 
     * @param string $address
     * @return void
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Returns the salaryExpectation
     * 
     * @return string salaryExpectation
     */
    public function getSalaryExpectation()
    {
        return $this->salaryExpectation;
    }

    /**
     * Sets the salaryExpectation
     * 
     * @param string $salaryExpectation
     * @return void
     */
    public function setSalaryExpectation($salaryExpectation)
    {
        $this->salaryExpectation = $salaryExpectation;
    }

    /**
     * Returns the earliestDateOfJoining
     * 
     * @return \DateTime earliestDateOfJoining
     */
    public function getEarliestDateOfJoining()
    {
        return $this->earliestDateOfJoining;
    }

    /**
     * Sets the earliestDateOfJoining
     * 
     * @param \DateTime $earliestDateOfJoining
     * @return void
     */
    public function setEarliestDateOfJoining(\DateTime $earliestDateOfJoining)
    {
        $this->earliestDateOfJoining = $earliestDateOfJoining;
    }

    /**
     * Returns the cv
     * 
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference cv
     */
    public function getCv()
    {
        return $this->cv;
    }

    /**
     * Sets the cv
     * 
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $cv
     * @return void
     */
    public function setCv(\TYPO3\CMS\Extbase\Domain\Model\FileReference $cv)
    {
        $this->cv = $cv;
    }

    /**
     * Returns the coverLetter
     * 
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference coverLetter
     */
    public function getCoverLetter()
    {
        return $this->coverLetter;
    }

    /**
     * Sets the coverLetter
     * 
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $coverLetter
     * @return void
     */
    public function setCoverLetter(\TYPO3\CMS\Extbase\Domain\Model\FileReference $coverLetter)
    {
        $this->coverLetter = $coverLetter;
    }

    /**
     * Returns the testimonials
     * 
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference testimonials
     */
    public function getTestimonials()
    {
        return $this->testimonials;
    }

    /**
     * Sets the testimonials
     * 
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $testimonials
     * @return void
     */
    public function setTestimonials(\TYPO3\CMS\Extbase\Domain\Model\FileReference $testimonials)
    {
        $this->testimonials = $testimonials;
    }

    /**
     * Returns the otherFiles
     * 
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference otherFiles
     */
    public function getOtherFiles()
    {
        return $this->otherFiles;
    }

    /**
     * Sets the otherFiles
     * 
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $otherFiles
     * @return void
     */
    public function setOtherFiles(\TYPO3\CMS\Extbase\Domain\Model\FileReference $otherFiles)
    {
        $this->otherFiles = $otherFiles;
    }

    /**
     * Returns the privacyAgreement
     * 
     * @return bool privacyAgreement
     */
    public function getPrivacyAgreement()
    {
        return $this->privacyAgreement;
    }

    /**
     * Sets the privacyAgreement
     * 
     * @param bool $privacyAgreement
     * @return void
     */
    public function setPrivacyAgreement($privacyAgreement)
    {
        $this->privacyAgreement = $privacyAgreement;
    }

    /**
     * Returns the boolean state of privacyAgreement
     * 
     * @return bool privacyAgreement
     */
    public function isPrivacyAgreement()
    {
        return $this->privacyAgreement;
    }

    /**
     * Returns the posting
     * 
     * @return int $posting
     */
    public function getPosting()
    {
        return $this->posting;
    }

    /**
     * Sets the posting
     * 
     * @param int $posting
     * @return void
     */
    public function setPosting(int $posting)
    {
        $this->posting = $posting;
    }
}
