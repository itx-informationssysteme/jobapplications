<?php

	namespace ITX\Jobapplications\Controller;

	use ITX\Jobapplications\Domain\Model\Constraint;
	use ITX\Jobapplications\Domain\Model\Location;
	use ITX\Jobapplications\Domain\Model\Region;
	use ITX\Jobapplications\Domain\Model\Posting;
	use ITX\Jobapplications\Domain\Repository\LocationRepository;
	use ITX\Jobapplications\Domain\Repository\RegionRepository;
	use ITX\Jobapplications\Domain\Repository\PostingRepository;
	use ITX\Jobapplications\Event\DisplayPostingEvent;
	use ITX\Jobapplications\PageTitle\JobsPageTitleProvider;
	use Psr\Http\Message\ResponseInterface;
	use TYPO3\CMS\Core\Cache\CacheManager;
	use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
	use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
	use TYPO3\CMS\Core\Context\Context;
	use TYPO3\CMS\Core\Error\Http\PageNotFoundException;
	use TYPO3\CMS\Core\Http\ImmediateResponseException;
	use TYPO3\CMS\Core\Page\PageRenderer;
	use TYPO3\CMS\Core\Pagination\SimplePagination;
	use TYPO3\CMS\Core\Utility\DebugUtility;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Core\Utility\MathUtility;
	use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
	use TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException;
	use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
	use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
	use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
	use TYPO3\CMS\Extbase\Persistence\QueryInterface;
	use TYPO3\CMS\Extbase\Reflection\Exception\UnknownClassException;
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
	class PostingController extends ActionController
	{
		protected PostingRepository $postingRepository;
		protected LocationRepository $locationRepository;
		protected PageRenderer $pageRenderer;

		public function __construct(PageRenderer $pageRenderer)
		{
			$this->pageRenderer = $pageRenderer;
		}

		/**
		 * initialize show action
		 *
		 * @param void
		 */
		public function initializeShowAction(): void
		{
			// If application form an posting are on the same page, the posting object is part of the application plugin.
			if (!$this->request->hasArgument("posting") && isset($_REQUEST["tx_jobapplications_applicationform"]["posting"]))
			{
				$this->request->setArgument("posting", $_REQUEST["tx_jobapplications_applicationform"]["posting"]);
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
		 * @throws InvalidQueryException
		 * @throws UnknownClassException
		 * @throws NoSuchArgumentException
		 */
		public function listAction(Constraint $constraint = null): ResponseInterface
		{
			$page = $this->request->hasArgument('page') ? (int)$this->request->getArgument('page') : 1;
			$itemsPerPage = $this->settings['itemsOnPage'] ?? 9;

			// Plugin selected categories
			$category_str = $this->settings["categories"];
			$categories = !empty($category_str) ? explode(",", $category_str) : [];

			$orderBy = $this->settings['list']['ordering']['field'] ?: 'date_posted';
			$order = '';
			switch ($this->settings['list']['ordering']['order'])
			{
				case 'descending':
					$order = QueryInterface::ORDER_DESCENDING;
					break;
				case 'ascending':
					$order = QueryInterface::ORDER_ASCENDING;
					break;
				default:
					$order = QueryInterface::ORDER_DESCENDING;
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

			// Determines whether user tried to filter
			$isFiltering = false;

			if ($constraint instanceof Constraint)
			{
				$isFiltering = true;
			}

			$paginator = new QueryResultPaginator($postings, $page, $itemsPerPage);

			$pagination = new SimplePagination($paginator);

			$this->view->assign('postings', $paginator->getPaginatedItems());
			$this->view->assign('paginator', $paginator);
			$this->view->assign('pagination', $pagination);
			$this->view->assign('isFiltering', $isFiltering);
			$this->view->assign('filterOptions', $filterOptions);
			$this->view->assign('constraint', $constraint);

			return $this->htmlResponse();
		}

		/**
		 * @param array $categories
		 *
		 * @return array
		 * @throws InvalidQueryException
		 */
		private function getCachedFilterOptions(array $categories): array
		{
			$contentObj = $this->configurationManager->getContentObject();
			if ($contentObj === null)
			{
				throw new \RuntimeException("Could not retrieve content object. Make sure to call this with a plugin.");
			}

			$pageId = $contentObj->data['pid'];
			if (!MathUtility::canBeInterpretedAsInteger($pageId))
			{
				throw new \RuntimeException("Page id $pageId is not valid.");
			}

			$cacheKey = "filterOptions-$pageId";

			/** @var FrontendInterface $cache */
			$cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('jobapplications_cache');
			$languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
			$languageId = $languageAspect->getId();

			// If $entry is false, or language key does not exist it hasn't been cached. Calculate the value and store it in the cache:
			if (($entry = $cache->get($cacheKey)) === false || !key_exists($languageId, $entry))
			{
				$entry = $this->getFilterOptions($categories, $languageId);

				$cache->set($cacheKey, $entry, [], null);
			}

			return $entry[$languageId];
		}

		/**
		 * This function makes calls to repositories to get all available filter options.
		 * These then get cached for performance reasons.
		 * Override for customization.
		 *
		 * @param $categories array categories
		 * @param $languageId int
		 *
		 * @return array
		 * @throws InvalidQueryException
		 */
		public function getFilterOptions(array $categories, $languageId): array
		{
			return [
				$languageId => [
					'division' => $this->postingRepository->findAllDivisions($categories, $languageId),
					'careerLevel' => $this->postingRepository->findAllCareerLevels($categories, $languageId),
					'employmentType' => $this->postingRepository->findAllEmploymentTypes($categories, $languageId),
					'locations' => $this->locationRepository->findAll($categories)->toArray()
				]
			];
		}

		/**
		 * @param Posting|null $posting
		 *
		 * @return ResponseInterface
		 * @throws ImmediateResponseException
		 * @throws PageNotFoundException
		 * @throws \JsonException
		 */
		public function showAction(?Posting $posting = null): ResponseInterface
		{
			if ($posting === null)
			{
				/** @var ErrorController $errorController */
				$errorController = GeneralUtility::makeInstance(ErrorController::class);
				$response = $errorController->pageNotFoundAction($GLOBALS['TYPO3_REQUEST'], 'Posting not available');
				throw new ImmediateResponseException($response, 1599638331);
			}

			$titleProvider = GeneralUtility::makeInstance(JobsPageTitleProvider::class);

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



			$this->addGoogleJobsDataToPage($posting);

			//  Event DisplayPostingEvent
			/** @var DisplayPostingEvent $event */
			$event = $this->eventDispatcher->dispatch(new DisplayPostingEvent($posting));
			$posting = $event->getPosting();

			$this->view->assign('posting', $posting);

			return $this->htmlResponse();
		}

		/**
		 * This function generates the Google Jobs structured on page data.
		 * This can be overriden if any field customizations are done.
		 */
		protected function addGoogleJobsDataToPage(Posting $posting): void
		{
			/** @var ExtensionConfiguration $extconf */
			$extconf = GeneralUtility::makeInstance(ExtensionConfiguration::class);

			$companyName = $extconf->get('jobapplications', 'companyName');

			if (empty($companyName) || $this->settings['enableGoogleJobs'] !== "1")
			{
				return;
			}

			$hiringOrganization = [
				"@type" => "Organization",
				"name" => $companyName
			];

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

			$arrayLocations = [];
			/** @var Location $location */
			foreach($posting->getLocations() as $location) {
				$arrayLocations[] =  [
					"@type" => "Place",
					"address" => [
						"streetAddress" => $location->getAddressStreetAndNumber(),
						"addressLocality" => $location->getAddressCity(),
						"postalCode" => $location->getAddressPostCode(),
						"addressCountry" => $location->getAddressCountry(),
						"addressRegion" => $location->getAddressRegion()
					]
				];
			}



			$googleJobsJSON = [
				"@context" => "http://schema.org",
				"@type" => "JobPosting",
				"datePosted" => $posting->getDatePosted()->format("c"),
				"description" => $posting->getCompanyDescription()."<br>".$posting->getJobDescription()."<br>"
					.$posting->getRoleDescription()."<br>".$posting->getSkillRequirements()
					."<br>".$posting->getBenefits(),
				"jobLocation" => $arrayLocations,
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

			$this->pageRenderer->addHeaderData($googleJobs);
		}

		public function injectPostingRepository(PostingRepository $postingRepository): void
		{
			$this->postingRepository = $postingRepository;
		}

		public function injectLocationRepository(LocationRepository $locationRepository): void
		{
			$this->locationRepository = $locationRepository;
		}
	}
