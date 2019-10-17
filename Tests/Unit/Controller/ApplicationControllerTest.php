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
    public function listActionFetchesAllApplicationsFromRepositoryAndAssignsThemToView()
    {

        $allApplications = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $applicationRepository = $this->getMockBuilder(\::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $applicationRepository->expects(self::once())->method('findAll')->will(self::returnValue($allApplications));
        $this->inject($this->subject, 'applicationRepository', $applicationRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('applications', $allApplications);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenApplicationToView()
    {
        $application = new \ITX\Jobs\Domain\Model\Application();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('application', $application);

        $this->subject->showAction($application);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenApplicationToApplicationRepository()
    {
        $application = new \ITX\Jobs\Domain\Model\Application();

        $applicationRepository = $this->getMockBuilder(\::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $applicationRepository->expects(self::once())->method('add')->with($application);
        $this->inject($this->subject, 'applicationRepository', $applicationRepository);

        $this->subject->createAction($application);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenApplicationToView()
    {
        $application = new \ITX\Jobs\Domain\Model\Application();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('application', $application);

        $this->subject->editAction($application);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenApplicationInApplicationRepository()
    {
        $application = new \ITX\Jobs\Domain\Model\Application();

        $applicationRepository = $this->getMockBuilder(\::class)
            ->setMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $applicationRepository->expects(self::once())->method('update')->with($application);
        $this->inject($this->subject, 'applicationRepository', $applicationRepository);

        $this->subject->updateAction($application);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenApplicationFromApplicationRepository()
    {
        $application = new \ITX\Jobs\Domain\Model\Application();

        $applicationRepository = $this->getMockBuilder(\::class)
            ->setMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $applicationRepository->expects(self::once())->method('remove')->with($application);
        $this->inject($this->subject, 'applicationRepository', $applicationRepository);

        $this->subject->deleteAction($application);
    }
}
