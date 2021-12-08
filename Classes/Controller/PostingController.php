<?php

	namespace ITX\Jobapplications\Controller;

	use ITX\Jobapplications\Domain\Model\Constraint;
	use ITX\Jobapplications\Domain\Model\Posting;
	use ITX\Jobapplications\PageTitle\JobsPageTitleProvider;
	use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
	use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
	use TYPO3\CMS\Core\Http\ImmediateResponseException;
	use TYPO3\CMS\Core\Page\PageRenderer;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
	use TYPO3\CMS\Frontend\Controller\ErrorController;

	/***************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2020
	 *  All rights reserved
	 *
	 *  This script is part of the TYPO3 project. The TYPO3 project is
	 *  free software; you can redistribute it and/or modify
	 *  it under the terms of the GNU General Public License as published by
	 *  the Free Software Foundation; either version 3 of the License, or
	 *  (at your option) any later version.
	 *
	 *  The GNU General Public License can be found at
	 *  http://www.gnu.org/copyleft/gpl.html.
	 *
	 *  This script is distributed in the hope that it will be useful,
	 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
	 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 *  GNU General Public License for more details.
	 *
	 *  This copyright notice MUST APPEAR in all copies of the script!
	 ***************************************************************/

	/**
	 * PostingController
	 */
	class PostingController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
	{

		/**
		 * postingRepository
		 *
		 * @var \ITX\Jobapplications\Domain\Repository\PostingRepository
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		protected $postingRepository = null;

		/**
		 * locationRepository
		 *
		 * @var \ITX\Jobapplications\Domain\Repository\LocationRepository
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		protected $locationRepository = null;

		/**
		 * signalSlotDispatcher
		 *
		 * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		protected $signalSlotDispatcher;

		/**
		 * initialize show action
		 *
		 * @param void
		 */
		public function initializeShowAction()
		{
			// If application form an posting are on the same page, the posting object is part of the application plugin.
			if (!$this->request->hasArgument("posting"))
			{
				if (isset($_REQUEST["tx_jobapplications_applicationform"]["posting"]))
				{
					$this->request->setArgument("posting", $_REQUEST["tx_jobapplications_applicationform"]["posting"]);
				}
			}
		}

		/**
		 * Initializes the view before invoking an action method.
		 *
		 * Override this method to solve assign variables common for all actions
		 * or prepare the view in another way before the action is called.
		 *
		 * @param ViewInterface $view The view to be initialized
		 */
		public function initializeView(ViewInterface $view)
		{
			if (is_object($GLOBALS['TSFE']))
			{
				$view->assign('pageData', $GLOBALS['TSFE']->page);
			}
			parent::initializeView($view);
		}

		/**
		 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
		 * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
		 * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
		 * @throws \TYPO3\CMS\Extbase\Reflection\Exception\UnknownClassException
		 */
		public function listAction(Constraint $constraint = null): void
		{
			// Plugin selected categories
			$category_str = $this->settings["categories"];
			$categories = !empty($category_str) ? explode(",", $category_str) : [];

			$orderBy = $this->settings['list']['ordering']['field'] ?: 'date_posted';
			$order = '';
			switch ($this->settings['list']['ordering']['order'])
			{
				case 'descending':
					$order = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING;
					break;
				case 'ascending':
					$order = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING;
					break;
				default:
					$order = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING;
			}

			// Get repository configuration from typoscript
			$repositoryConfiguration = $this->settings['filter']['repositoryConfiguration'];
			if (!$repositoryConfiguration)
			{
				$repositoryConfiguration = [];
			}

			// Get cached filter options
			$filterOptions = $this->getCachedFilterOptions($categories);

			// Make the actual repository call
			$postings = $this->postingRepository->findByFilter($categories, $repositoryConfiguration, $constraint, $orderBy, $order);

			// SignalSlotDispatcher BeforePostingAssign
			$changedPostings = $this->signalSlotDispatcher->dispatch(__CLASS__, "BeforePostingAssign", ["postings" => $postings]);
			if (count($changedPostings["postings"]) > 0)
			{
				$postings = $changedPostings['postings'];
			}

			// Determines whether user tried to filter
			$isFiltering = false;

			if ($constraint instanceof Constraint)
			{
				$isFiltering = true;
			}

			$this->view->assign('postings', $postings);
			$this->view->assign('isFiltering', $isFiltering);
			$this->view->assign('filterOptions', $filterOptions);
			$this->view->assign('constraint', $constraint);
		}

		/**
		 * @param $categories
		 *
		 * @return array
		 */
		private function getCachedFilterOptions($categories): array
		{
			/** @var FrontendInterface $cache */
			$cache = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class)->getCache('jobapplications_cache');

			// If $entry is false, it hasn't been cached. Calculate the value and store it in the cache:
			if (($entry = $cache->get('filterOptions')) === false)
			{
				$entry = $this->getFilterOptions($categories);

				$cache->set('filterOptions', $entry, [], null);
			}

			return $entry;
		}

		/**
		 * This function makes calls to repositories to get all available filter options.
		 * These then get cached for performance reasons.
		 * Override for customization.
		 *
		 * @param $categories array categories
		 *
		 * @return array
		 */
		public function getFilterOptions($categories): array
		{
			return [
				'division' => $this->postingRepository->findAllDivisions($categories),
				'careerLevel' => $this->postingRepository->findAllCareerLevels($categories),
				'employmentType' => $this->postingRepository->findAllEmploymentTypes($categories),
				'location' => $this->locationRepository->findAll($categories)->toArray(),
			];
		}

		/**
		 * @param \ITX\Jobapplications\Domain\Model\Posting|null $posting
		 *
		 * @throws ImmediateResponseException
		 * @throws \JsonException
		 * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
		 * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
		 * @throws \TYPO3\CMS\Core\Error\Http\PageNotFoundException
		 * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
		 * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
		 */
		public function showAction(\ITX\Jobapplications\Domain\Model\Posting $posting = null): void
		{
			if ($posting === null)
			{
				/** @var ErrorController $errorController */
				$errorController = GeneralUtility::makeInstance(ErrorController::class);
				$response = $errorController->pageNotFoundAction($GLOBALS['TYPO3_REQUEST'], 'Posting not available');
				throw new ImmediateResponseException($response, 1599638331);
			}

			$titleProvider = GeneralUtility::makeInstance(JobsPageTitleProvider::class);

			/** @var ExtensionConfiguration $extconf */
			$extconf = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class);

			//Google Jobs

			$hiringOrganization = [
				"@type" => "Organization",
				"name" => $extconf->get('jobapplications', 'companyName')
			];

			if (!empty($hiringOrganization['name']) && $this->settings['enableGoogleJobs'] == "1")
			{
				$logo = $extconf->get('jobapplications', 'logo');
				if (!empty($logo))
				{
					$hiringOrganization["logo"] = $logo;
				}

				$employmentTypes = [];

				foreach ($posting->getDeserializedEmploymentTypes() as $employmentType)
				{
					switch ($employmentType)
					{
						case "fulltime":
							$employmentTypes[] = "FULL_TIME";
							break;
						case "parttime":
							$employmentTypes[] = "PART_TIME";
							break;
						case "contractor":
							$employmentTypes[] = "CONTRACTOR";
							break;
						case "temporary":
							$employmentTypes[] = "TEMPORARY";
							break;
						case "intern":
							$employmentTypes[] = "INTERN";
							break;
						case "volunteer":
							$employmentTypes[] = "VOLUNTEER";
							break;
						case "perdiem":
							$employmentTypes[] = "PER_DIEM";
							break;
						case "other":
							$employmentTypes[] = "OTHER";
							break;
						default:
							$employmentTypes = [];
					}
				}

				$googleJobsJSON = [
					"@context" => "http://schema.org",
					"@type" => "JobPosting",
					"datePosted" => $posting->getDatePosted()->format("c"),
					"description" => $posting->getCompanyDescription()."<br>".$posting->getJobDescription()."<br>"
						.$posting->getRoleDescription()."<br>".$posting->getSkillRequirements()
						."<br>".$posting->getBenefits(),
					"jobLocation" => [
						"@type" => "Place",
						"address" => [
							"streetAddress" => $posting->getLocation()->getAddressStreetAndNumber(),
							"addressLocality" => $posting->getLocation()->getAddressCity(),
							"postalCode" => $posting->getLocation()->getAddressPostCode(),
							"addressCountry" => $posting->getLocation()->getAddressCountry()
						]
					],
					"title" => $posting->getTitle(),
					"employmentType" => $employmentTypes
				];

				$googleJobsJSON["hiringOrganization"] = $hiringOrganization;

				if (!empty($posting->getBaseSalary()))
				{
					$currency = $logo = $extconf->get('jobapplications', 'currency') ?: "EUR";
					$googleJobsJSON["baseSalary"] = [
						"@type" => "MonetaryAmount",
						"currency" => $currency,
						"value" => [
							"@type" => "QuantitativeValue",
							"value" => preg_replace('/\D/', '', $posting->getBaseSalary()),
							"unitText" => "YEAR"
						]
					];
				}

				if ($posting->getEndtime() instanceof \DateTime)
				{
					$googleJobsJSON["validThrough"] = $posting->getEndtime()->format("c");
				}

				$googleJobs = "<script type=\"application/ld+json\">".json_encode($googleJobsJSON, JSON_THROW_ON_ERROR)."</script>";

				$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
				$pageRenderer->addHeaderData($googleJobs);
			}

			// Pagetitle Templating
			$title = $this->settings["pageTitle"];
			if ($title !== "")
			{
				$title = str_replace("%postingTitle%", $posting->getTitle(), $title);
			}
			else
			{
				$title = $posting->getTitle();
			}

			$titleProvider->setTitle($title);

			// SignalSlotDispatcher BeforePostingShowAssign
			$changedPosting = $this->signalSlotDispatcher->dispatch(__CLASS__, "BeforePostingShowAssign", ["posting" => $posting]);
			if ($changedPosting["posting"] instanceof Posting)
			{
				$posting = $changedPosting['posting'];
			}

			$this->view->assign('posting', $posting);
		}
	}
