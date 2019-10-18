<?php
namespace ITX\Jobs\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Stefanie Döll 
 * @author Benjamin Jasper 
 */
class ApplicationControllerTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \ITX\Jobs\Controller\ApplicationController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\ITX\Jobs\Controller\ApplicationController::class)
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
        $application = new \ITX\Jobs\Domain\Model\Application();

        $applicationRepository = $this->getMockBuilder(\ITX\Jobs\Domain\Repository\ApplicationRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $applicationRepository->expects(self::once())->method('add')->with($application);
        $this->inject($this->subject, 'applicationRepository', $applicationRepository);

        $this->subject->createAction($application);
    }
}
