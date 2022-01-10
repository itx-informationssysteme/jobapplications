<?php

	namespace ITX\Jobapplications\Tests\Unit\Controller;
	
	use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
	use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
	use ITX\Jobapplications\Domain\Repository\LocationRepository;
	use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
	use ITX\Jobapplications\Domain\Model\Location;
	use ITX\Jobapplications\Controller\LocationController;
	/**
	 * Test case.
	 *
	 * @author Stefanie Döll
	 * @author Benjamin Jasper
	 */
	class LocationControllerTest extends UnitTestCase
	{
		/**
		 * @var \ITX\Jobapplications\Controller\LocationController
		 */
		protected $subject = null;

		/**
		 * @test
		 */
		public function listActionFetchesAllLocationsFromRepositoryAndAssignsThemToView()
		{

			$allLocations = $this->getMockBuilder(ObjectStorage::class)
								 ->disableOriginalConstructor()
								 ->getMock();

			$locationRepository = $this->getMockBuilder(LocationRepository::class)
									   ->setMethods(['findAll'])
									   ->disableOriginalConstructor()
									   ->getMock();
			$locationRepository->expects(self::once())->method('findAll')->will(self::returnValue($allLocations));
			$this->inject($this->subject, 'locationRepository', $locationRepository);

			$view = $this->getMockBuilder(ViewInterface::class)->getMock();
			$view->expects(self::once())->method('assign')->with('locations', $allLocations);
			$this->inject($this->subject, 'view', $view);

			$this->subject->listAction();
		}

		/**
		 * @test
		 */
		public function showActionAssignsTheGivenLocationToView()
		{
			$location = new Location();

			$view = $this->getMockBuilder(ViewInterface::class)->getMock();
			$this->inject($this->subject, 'view', $view);
			$view->expects(self::once())->method('assign')->with('location', $location);

			$this->subject->showAction($location);
		}

		/**
		 * @test
		 */
		public function createActionAddsTheGivenLocationToLocationRepository()
		{
			$location = new Location();

			$locationRepository = $this->getMockBuilder(LocationRepository::class)
									   ->setMethods(['add'])
									   ->disableOriginalConstructor()
									   ->getMock();

			$locationRepository->expects(self::once())->method('add')->with($location);
			$this->inject($this->subject, 'locationRepository', $locationRepository);

			$this->subject->createAction($location);
		}

		/**
		 * @test
		 */
		public function editActionAssignsTheGivenLocationToView()
		{
			$location = new Location();

			$view = $this->getMockBuilder(ViewInterface::class)->getMock();
			$this->inject($this->subject, 'view', $view);
			$view->expects(self::once())->method('assign')->with('location', $location);

			$this->subject->editAction($location);
		}

		/**
		 * @test
		 */
		public function updateActionUpdatesTheGivenLocationInLocationRepository()
		{
			$location = new Location();

			$locationRepository = $this->getMockBuilder(LocationRepository::class)
									   ->setMethods(['update'])
									   ->disableOriginalConstructor()
									   ->getMock();

			$locationRepository->expects(self::once())->method('update')->with($location);
			$this->inject($this->subject, 'locationRepository', $locationRepository);

			$this->subject->updateAction($location);
		}

		/**
		 * @test
		 */
		public function deleteActionRemovesTheGivenLocationFromLocationRepository()
		{
			$location = new Location();

			$locationRepository = $this->getMockBuilder(LocationRepository::class)
									   ->setMethods(['remove'])
									   ->disableOriginalConstructor()
									   ->getMock();

			$locationRepository->expects(self::once())->method('remove')->with($location);
			$this->inject($this->subject, 'locationRepository', $locationRepository);

			$this->subject->deleteAction($location);
		}

		protected function setUp()
		{
			parent::setUp();
			$this->subject = $this->getMockBuilder(LocationController::class)
								  ->setMethods(['redirect', 'forward', 'addFlashMessage'])
								  ->disableOriginalConstructor()
								  ->getMock();
		}

		protected function tearDown()
		{
			parent::tearDown();
		}
	}
