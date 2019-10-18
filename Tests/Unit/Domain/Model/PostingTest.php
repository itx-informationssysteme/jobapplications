<?php
namespace ITX\Jobs\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Stefanie Döll 
 * @author Benjamin Jasper 
 */
class PostingTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \ITX\Jobs\Domain\Model\Posting
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \ITX\Jobs\Domain\Model\Posting();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle()
    {
        $this->subject->setTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'title',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getDatePostedReturnsInitialValueForDateTime()
    {
        self::assertEquals(
            null,
            $this->subject->getDatePosted()
        );
    }

    /**
     * @test
     */
    public function setDatePostedForDateTimeSetsDatePosted()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setDatePosted($dateTimeFixture);

        self::assertAttributeEquals(
            $dateTimeFixture,
            'datePosted',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getCareerLevelReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCareerLevel()
        );
    }

    /**
     * @test
     */
    public function setCareerLevelForStringSetsCareerLevel()
    {
        $this->subject->setCareerLevel('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'careerLevel',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getDivisionReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getDivision()
        );
    }

    /**
     * @test
     */
    public function setDivisionForStringSetsDivision()
    {
        $this->subject->setDivision('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'division',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getEmploymentTypeReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getEmploymentType()
        );
    }

    /**
     * @test
     */
    public function setEmploymentTypeForStringSetsEmploymentType()
    {
        $this->subject->setEmploymentType('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'employmentType',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTermsOfEmploymentReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTermsOfEmployment()
        );
    }

    /**
     * @test
     */
    public function setTermsOfEmploymentForStringSetsTermsOfEmployment()
    {
        $this->subject->setTermsOfEmployment('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'termsOfEmployment',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getCompanyDescriptionReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCompanyDescription()
        );
    }

    /**
     * @test
     */
    public function setCompanyDescriptionForStringSetsCompanyDescription()
    {
        $this->subject->setCompanyDescription('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'companyDescription',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getJobDescriptionReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getJobDescription()
        );
    }

    /**
     * @test
     */
    public function setJobDescriptionForStringSetsJobDescription()
    {
        $this->subject->setJobDescription('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'jobDescription',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getRoleDescriptionReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getRoleDescription()
        );
    }

    /**
     * @test
     */
    public function setRoleDescriptionForStringSetsRoleDescription()
    {
        $this->subject->setRoleDescription('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'roleDescription',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getSkillRequirementsReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSkillRequirements()
        );
    }

    /**
     * @test
     */
    public function setSkillRequirementsForStringSetsSkillRequirements()
    {
        $this->subject->setSkillRequirements('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'skillRequirements',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getBenefitsReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getBenefits()
        );
    }

    /**
     * @test
     */
    public function setBenefitsForStringSetsBenefits()
    {
        $this->subject->setBenefits('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'benefits',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getBaseSalaryReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getBaseSalary()
        );
    }

    /**
     * @test
     */
    public function setBaseSalaryForStringSetsBaseSalary()
    {
        $this->subject->setBaseSalary('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'baseSalary',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getValidThroughReturnsInitialValueForDateTime()
    {
        self::assertEquals(
            null,
            $this->subject->getValidThrough()
        );
    }

    /**
     * @test
     */
    public function setValidThroughForDateTimeSetsValidThrough()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setValidThrough($dateTimeFixture);

        self::assertAttributeEquals(
            $dateTimeFixture,
            'validThrough',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getRequiredDocumentsReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getRequiredDocuments()
        );
    }

    /**
     * @test
     */
    public function setRequiredDocumentsForStringSetsRequiredDocuments()
    {
        $this->subject->setRequiredDocuments('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'requiredDocuments',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getCompanyInformationReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCompanyInformation()
        );
    }

    /**
     * @test
     */
    public function setCompanyInformationForStringSetsCompanyInformation()
    {
        $this->subject->setCompanyInformation('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'companyInformation',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getLocationReturnsInitialValueForLocation()
    {
        self::assertEquals(
            null,
            $this->subject->getLocation()
        );
    }

    /**
     * @test
     */
    public function setLocationForLocationSetsLocation()
    {
        $locationFixture = new \ITX\Jobs\Domain\Model\Location();
        $this->subject->setLocation($locationFixture);

        self::assertAttributeEquals(
            $locationFixture,
            'location',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getContactReturnsInitialValueForContact()
    {
        self::assertEquals(
            null,
            $this->subject->getContact()
        );
    }

    /**
     * @test
     */
    public function setContactForContactSetsContact()
    {
        $contactFixture = new \ITX\Jobs\Domain\Model\Contact();
        $this->subject->setContact($contactFixture);

        self::assertAttributeEquals(
            $contactFixture,
            'contact',
            $this->subject
        );
    }
}
