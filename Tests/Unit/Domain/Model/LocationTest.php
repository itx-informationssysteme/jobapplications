<?php
namespace ITX\Jobapplications\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Stefanie Döll 
 * @author Benjamin Jasper 
 */
class LocationTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \ITX\Jobapplications\Domain\Model\Location
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \ITX\Jobapplications\Domain\Model\Location();
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
    public function getAddressReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getAddress()
        );
    }

    /**
     * @test
     */
    public function setAddressForStringSetsAddress()
    {
        $this->subject->setAddress('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'address',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getLatitudeReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getLatitude()
        );
    }

    /**
     * @test
     */
    public function setLatitudeForStringSetsLatitude()
    {
        $this->subject->setLatitude('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'latitude',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getLonditudeReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getLonditude()
        );
    }

    /**
     * @test
     */
    public function setLonditudeForStringSetsLonditude()
    {
        $this->subject->setLonditude('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'londitude',
            $this->subject
        );
    }
}
