<?php

	namespace ITX\Jobapplications\Tests\Unit\Controller;
	
	use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
	use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
	use ITX\Jobapplications\Domain\Repository\PostingRepository;
	use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
	use ITX\Jobapplications\Domain\Model\Posting;
	use ITX\Jobapplications\Controller\PostingController;
	/**
	 * Test case.
	 *
	 * @author Stefanie Döll
	 * @author Benjamin Jasper
	 */
	class PostingControllerTest extends UnitTestCase
	{
		/**
		 * @var \ITX\Jobapplications\Controller\PostingController
		 */
		protected $subject = null;

		/**
		 * @test
		 */
		public function listActionFetchesAllPostingsFromRepositoryAndAssignsThemToView()
		{

			$allPostings = $this->getMockBuilder(ObjectStorage::class)
								->disableOriginalConstructor()
								->getMock();

			$postingRepository = $this->getMockBuilder(PostingRepository::class)
									  ->setMethods(['findAll'])
									  ->disableOriginalConstructor()
									  ->getMock();
			$postingRepository->expects(self::once())->method('findAll')->will(self::returnValue($allPostings));
			$this->inject($this->subject, 'postingRepository', $postingRepository);

			$view = $this->getMockBuilder(ViewInterface::class)->getMock();
			$view->expects(self::once())->method('assign')->with('postings', $allPostings);
			$this->inject($this->subject, 'view', $view);

			$this->subject->listAction();
		}

		/**
		 * @test
		 */
		public function showActionAssignsTheGivenPostingToView()
		{
			$posting = new Posting();

			$view = $this->getMockBuilder(ViewInterface::class)->getMock();
			$this->inject($this->subject, 'view', $view);
			$view->expects(self::once())->method('assign')->with('posting', $posting);

			$this->subject->showAction($posting);
		}

		protected function setUp()
		{
			parent::setUp();
			$this->subject = $this->getMockBuilder(PostingController::class)
								  ->setMethods(['redirect', 'forward', 'addFlashMessage'])
								  ->disableOriginalConstructor()
								  ->getMock();
		}

		protected function tearDown()
		{
			parent::tearDown();
		}
	}
