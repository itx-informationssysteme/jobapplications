<?php
namespace ITX\Jobapplications\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Stefanie Döll 
 * @author Benjamin Jasper 
 */
class ApplicationControllerTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \ITX\Jobapplications\Controller\ApplicationController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\ITX\Jobapplications\Controller\ApplicationController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenApplicationToApplicationRepository()
    {
        $application = new \ITX\Jobapplications\Domain\Model\Application();

        $applicationRepository = $this->getMockBuilder(\ITX\Jobapplications\Domain\Repository\ApplicationRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $applicationRepository->expects(self::once())->method('add')->with($application);
        $this->inject($this->subject, 'applicationRepository', $applicationRepository);

        $this->subject->createAction($application);
    }
}
