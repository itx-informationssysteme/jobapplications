<?php

	namespace ITX\Jobs\Controller;

	use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
	use ITX\Jobs\PageTitle\JobsPageTitleProvider;

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
	 * PostingController
	 */
	class PostingController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
	{

		/**
		 * postingRepository
		 *
		 * @var \ITX\Jobs\Domain\Repository\PostingRepository
		 * @inject
		 */
		protected $postingRepository = null;

		/**
		 * locationRepository
		 *
		 * @var \ITX\Jobs\Domain\Repository\LocationRepository
		 * @inject
		 */
		protected $locationRepository = null;

		public function initializeAction()
		{

		}

		/**
		 * action list
		 *
		 * @param ITX\Jobs\Domain\Model\Posting
		 *
		 * @return void
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
		 */
		public function listAction()
		{
			$divisionName = "";
			$careerLevelType = "";
			$selectedEmploymentType = "";
			$selectedLocation = "";
			$category_str = $this->settings["categories"];
			$categories = array();

			if ($category_str != '')
			{
				$categories = explode(",", $category_str);
			}

			if ($this->request->hasArgument("division") ||
				$this->request->hasArgument("careerLevel") ||
				$this->request->hasArgument("employmentType" ||
											$this->request->hasArgument("location")))
			{
				$divisionName = $this->request->getArgument('division');
				$careerLevelType = $this->request->getArgument('careerLevel');
				$selectedEmploymentType = $this->request->getArgument('employmentType');
				$selectedLocation = $this->request->getArgument('location');
			}
			if ($divisionName != "" || $careerLevelType != "" || $selectedEmploymentType != "" || $selectedLocation != "")
			{
				$postings = $this->postingRepository->findByFilter($divisionName, $careerLevelType, $selectedEmploymentType, $selectedLocation, $categories);

			}
			else
			{
				if (count($categories) == 0)
				{
					$postings = $this->postingRepository->findAll();
				}
				else
				{
					$postings = $this->postingRepository->findByCategory($categories);
				}
			}

			$divisions = $this->postingRepository->findAllDivisions($categories);
			$careerLevels = $this->postingRepository->findAllCareerLevels($categories);
			$employmentTypes = $this->postingRepository->findAllEmploymentTypes($categories);
			$locations = $this->locationRepository->findAll($categories);

			$this->view->assign('divisionName', $divisionName);
			$this->view->assign('careerLevelType', $careerLevelType);
			$this->view->assign('selectedEmploymentType', $selectedEmploymentType);
			$this->view->assign('selectedLocation', $selectedLocation);
			$this->view->assign('employmentTypes', $employmentTypes);
			$this->view->assign('postings', $postings);
			$this->view->assign('divisions', $divisions);
			$this->view->assign('careerLevels', $careerLevels);
			$this->view->assign('locations', $locations);
		}

		/**
		 * action show
		 *
		 * @param ITX\Jobs\Domain\Model\Posting
		 *
		 * @return void
		 */
		public function showAction(\ITX\Jobs\Domain\Model\Posting $posting = null)
		{

			$titleProvider = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(JobsPageTitleProvider::class);

			$title = $this->settings["pageTitle"];
			if ($title != "")
			{
				$title = str_replace("%postingTitle%", $posting->getTitle(), $title);
			} else {
				$title = $posting->getTitle();
			}

			$titleProvider->setTitle($title);
			$this->view->assign('posting', $posting);

		}

		/**
		 * action
		 *
		 * @return void
		 */
		public function Action()
		{
		}
	}
