<?php
namespace ITX\Jobs\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Stefanie Döll 
 * @author Benjamin Jasper 
 */
class ContactTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \ITX\Jobs\Domain\Model\Contact
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \ITX\Jobs\Domain\Model\Contact();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getNameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function setNameForStringSetsName()
    {
        $this->subject->setName('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'name',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getEmailReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getEmail()
        );
    }

    /**
     * @test
     */
    public function setEmailForStringSetsEmail()
    {
        $this->subject->setEmail('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'email',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPhoneReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getPhone()
        );
    }

    /**
     * @test
     */
    public function setPhoneForStringSetsPhone()
    {
        $this->subject->setPhone('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'phone',
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
}
