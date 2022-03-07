<?php

	namespace ITX\Jobapplications\Tests\Unit\Controller;
	
	use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
	use ITX\Jobapplications\Domain\Model\Application;
	use ITX\Jobapplications\Domain\Repository\ApplicationRepository;
	use ITX\Jobapplications\Controller\ApplicationController;
	/**
	 * Test case.
	 *
	 * @author Stefanie Döll
	 * @author Benjamin Jasper
	 */
	class ApplicationControllerTest extends UnitTestCase
	{
		/**
		 * @var ApplicationController
		 */
		protected $subject = null;

		/**
		 * @test
		 */
		public function createActionAddsTheGivenApplicationToApplicationRepository()
		{
			$application = new Application();

			$applicationRepository = $this->getMockBuilder(ApplicationRepository::class)
										  ->setMethods(['add'])
										  ->disableOriginalConstructor()
										  ->getMock();

			$applicationRepository->expects(self::once())->method('add')->with($application);
			$this->inject($this->subject, 'applicationRepository', $applicationRepository);

			$this->subject->createAction($application);
		}

		protected function setUp(): void
		{
			parent::setUp();
			$this->subject = $this->getMockBuilder(ApplicationController::class)
								  ->setMethods(['redirect', 'forward', 'addFlashMessage'])
								  ->disableOriginalConstructor()
								  ->getMock();
		}

		protected function tearDown(): void
		{
			parent::tearDown();
		}
	}
