<?php

	namespace ITX\Jobs\Controller;

	use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
	use TYPO3\CMS\Core\Page\PageRenderer;
	use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
	use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
	use ITX\Jobs\PageTitle\JobsPageTitleProvider;
	use TYPO3\CMS\Core\Utility\GeneralUtility;

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
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		protected $postingRepository = null;

		/**
		 * locationRepository
		 *
		 * @var \ITX\Jobs\Domain\Repository\LocationRepository
		 * @TYPO3\CMS\Extbase\Annotation\Inject
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

			$titleProvider = GeneralUtility::makeInstance(JobsPageTitleProvider::class);

			//Google Jobs
			$metaTagManager = GeneralUtility::makeInstance(MetaTagManagerRegistry::class);
			// @extensionScannerIgnoreLine
			$metaTagManager->getManagerForProperty("description")->addProperty("description", strip_tags($posting->getJobDescription()));
			// @extensionScannerIgnoreLine
			$metaTagManager->getManagerForProperty("og:title")->addProperty("og:title", $posting->getTitle());
			// @extensionScannerIgnoreLine
			$metaTagManager->getManagerForProperty("og:description")->addProperty("og:description", strip_tags($posting->getJobDescription()));
			if ($posting->getListViewImage())
			{
				// @extensionScannerIgnoreLine
				$metaTagManager->getManagerForProperty("og:image")->addProperty("og:image", $this->request->getBaseUri().$posting->getListViewImage()->getOriginalResource()->getPublicUrl());
			}

			$extconf = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class);

			$hiringOranization = array(
				"@type" => "Organization",
				"name" => $extconf->get('jobs', 'companyName')
			);

			if ($hiringOranization['name'] && $this->settings['enableGoogleJobs'])
			{
				$logo = $extconf->get('jobs', 'logo');
				if ($logo)
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

				if ($posting->getBaseSalary())
				{
					$googleJobsJSON["baseSalary"] = [
						"@type" => "MonetaryAmount",
						"currency" => "EUR",
						"value" => [
							"@type" => "QuantitativeValue",
							"value" => preg_replace('/\D/', '', $posting->getBaseSalary()),
							"unitText" => "YEAR"
						]
					];
				}
				if ($posting->getValidThrough())
				{
					$googleJobsJSON["validThrough"] = $posting->getValidThrough()->format("c");
				}

				$googleJobs = "<script type=\"application/ld+json\">".strval(json_encode($googleJobsJSON))."</script>";

				$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
				$pageRenderer->addHeaderData($googleJobs);
			}

			//Pagetitle Templating
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
