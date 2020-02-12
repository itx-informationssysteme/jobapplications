<?php

	namespace ITX\Jobapplications\Controller;

	use ScssPhp\ScssPhp\Formatter\Debug;
	use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
	use TYPO3\CMS\Core\Page\PageRenderer;
	use TYPO3\CMS\Core\Utility\DebugUtility;
	use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;
	use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
	use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
	use ITX\Jobapplications\PageTitle\JobsPageTitleProvider;
	use TYPO3\CMS\Core\Utility\GeneralUtility;

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
				if(isset($_REQUEST["tx_jobapplications_applicationform"]["posting"]))
				{
					$this->request->setArgument("posting", $_REQUEST["tx_jobapplications_applicationform"]["posting"]);
				}
			}
		}

		/**
		 * action list
		 *
		 * @return void
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
		 * @throws InvalidArgumentValueException
		 */
		public function listAction()
		{
			$divisionName = "";
			$careerLevelType = "";
			$selectedEmploymentType = "";
			$selectedLocation = -1;
			$category_str = $this->settings["categories"];
			$categories = array();

			if (!empty($category_str))
			{
				$categories = explode(",", $category_str);
			}

			$divisions = $this->postingRepository->findAllDivisions($categories);
			$careerLevels = $this->postingRepository->findAllCareerLevels($categories);
			$employmentTypes = $this->postingRepository->findAllEmploymentTypes($categories);
			$locations = $this->locationRepository->findAll($categories)->toArray();

			if ($this->request->hasArgument("division") &&
				$this->request->hasArgument("careerLevel") &&
				$this->request->hasArgument("employmentType") &&
				$this->request->hasArgument("location"))
			{
				$divisionName = $this->request->getArgument('division');
				$careerLevelType = $this->request->getArgument('careerLevel');
				$selectedEmploymentType = $this->request->getArgument('employmentType');
				$selectedLocation = $this->request->getArgument('location') ? intval($this->request->getArgument('location')) : -1;

				// Prepare for sanity check by aggregating all possible values
				$tmp_divisions = array_column($divisions, "division");
				$tmp_careerLevels = array_column($careerLevels, "careerLevel");
				$tmp_employmentTypes = array_column($employmentTypes, "employmentType");

				$tmp_divisions[] = "";
				$tmp_careerLevels[] = "";
				$tmp_employmentTypes[] = "";

				$locationUids = array_map(function ($element) {
					return $element->getUid();
				}, $locations);
				$locationUids[] = -1;

				// Check for user input sanity
				$result_division = in_array($divisionName, $tmp_divisions);
				$result_careerLevel = in_array($careerLevelType, $tmp_careerLevels);
				$result_employmentType = in_array($selectedEmploymentType, $tmp_employmentTypes);
				$result_location = in_array($selectedLocation, $locationUids);

				if (!$result_division || !$result_careerLevel || !$result_employmentType || !$result_location)
				{
					throw new InvalidArgumentValueException("Input not valid.");
				}
			}

			if (!empty($divisionName) || !empty($careerLevelType) || !empty($selectedEmploymentType) || $selectedLocation != -1)
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

			// SignalSlotDispatcher BeforePostingAssign
			$changedPostings = $this->signalSlotDispatcher->dispatch(__CLASS__, "BeforePostingAssign", ["postings" => $postings]);
			if (count($changedPostings["postings"]) > 0)
			{
				$postings = $changedPostings['postings'];
			}

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
		 * @param \ITX\Jobapplications\Domain\Model\Posting $posting
		 *
		 * @return void
		 */
		public function showAction(\ITX\Jobapplications\Domain\Model\Posting $posting = null)
		{

			$titleProvider = GeneralUtility::makeInstance(JobsPageTitleProvider::class);

			$extconf = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class);

			//Google Jobs

			$hiringOranization = array(
				"@type" => "Organization",
				"name" => $extconf->get('jobapplications', 'companyName')
			);

			if (!empty($hiringOranization['name']) && $this->settings['enableGoogleJobs'] == "1")
			{
				$logo = $extconf->get('jobapplications', 'logo');
				if (!empty($logo))
				{
					$hiringOranization['hiringOranization']["logo"] = $logo;
				}

				switch ($posting->getEmploymentType())
				{
					case "fulltime":
						$employmentType = "FULL_TIME";
						break;
					case "parttime":
						$employmentType = "PART_TIME";
						break;
					case "contractor":
						$employmentType = "CONTRACTOR";
						break;
					case "temporary":
						$employmentType = "TEMPORARY";
						break;
					case "intern":
						$employmentType = "INTERN";
						break;
					case "volunteer":
						$employmentType = "VOLUNTEER";
						break;
					case "perdiem":
						$employmentType = "PER_DIEM";
						break;
					case "other":
						$employmentType = "OTHER";
						break;
					default:
						$employmentType = "";
				}

				$googleJobsJSON = array(
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
							"postalCode" => strval($posting->getLocation()->getAddressPostCode()),
							"addressCountry" => $posting->getLocation()->getAddressCountry()
						]
					],
					"title" => $posting->getTitle(),
					"employmentType" => $employmentType
				);

				$googleJobsJSON["hiringOrganization"] = $hiringOranization;

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
				if ($posting->getValidThrough() instanceof \DateTime)
				{
					$googleJobsJSON["validThrough"] = $posting->getValidThrough()->format("c");
				}

				$googleJobs = "<script type=\"application/ld+json\">".strval(json_encode($googleJobsJSON))."</script>";

				$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
				$pageRenderer->addHeaderData($googleJobs);
			}

			// Pagetitle Templating
			$title = $this->settings["pageTitle"];
			if ($title != "")
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
