<?php

	namespace ITX\Jobs\Controller;

	use ITX\Jobs\Domain\Model\Contact;
	use ITX\Jobs\Domain\Model\Status;
	use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

	/**
	 * Class BackendController
	 *
	 * @package ITX\Jobs\Controller
	 */
	class BackendController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
	{
		/**
		 * applicationRepository
		 *
		 * @var \ITX\Jobs\Domain\Repository\ApplicationRepository
		 * @inject
		 */
		protected $applicationRepository = null;

		/**
		 * postingRepository
		 *
		 * @var \ITX\Jobs\Domain\Repository\PostingRepository
		 * @inject
		 */
		protected $postingRepoitory = null;

		/**
		 * contactRepository
		 *
		 * @var \ITX\Jobs\Domain\Repository\ContactRepository
		 * @inject
		 */
		protected $contactRepository = null;

		/**
		 * statusRepository
		 *
		 * @var \ITX\Jobs\Domain\Repository\StatusRepository
		 * @inject
		 */
		protected $statusRepository = null;

		/**
		 * persistenceManager
		 *
		 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
		 * @inject
		 */
		protected $persistenceManager;

		/**
		 * action listApplications
		 *
		 * @return void
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
		 */
		public function listApplicationsAction()
		{
			$sessionData = $GLOBALS["BE_USER"]->getSessionData("tx_jobs");

			// Get all filter elements and set them to empty if there are none and use session storage for persisting selection
			if ($this->request->hasArgument("submit"))
			{
				$this->request->hasArgument("contact") ? $selectedContact = $this->request->getArgument("contact") : $selectedContact = "";
				$this->request->hasArgument("archived") ? $archivedSelected = $this->request->getArgument("archived") : $archivedSelected = "";
				$this->request->hasArgument("posting") ? $selectedPosting = $this->request->getArgument("posting") : $selectedPosting = "";
			}
			else
			{
				$selectedPosting = $sessionData["selectedPosting"];
				$archivedSelected = $sessionData["archivedSelected"];
				$selectedContact = $sessionData["selectedContact"];
			}

			$contact = $this->getActiveBeContact();

			// Handling a status change, triggered in listApplications View
			if ($this->request->hasArgument("status"))
			{
				$application = $this->applicationRepository->findByUid($this->request->getArgument("application"));
				$application->setStatus($this->statusRepository->findByUid($this->request->getArgument("status")));
				$this->persistenceManager->update($application);
			}

			// Select contact automatically based on user who is accessing this
			if ($contact && $selectedContact == 'contact')
			{
				$selectedContact = $contact->getUid();
			}

			// Handle show archived applications when selected in frontend-backend
			if ($archivedSelected != "")
			{
				$archivedApplications = $this->applicationRepository->findByFilter($selectedContact, $selectedPosting, 1);
				$this->view->assign("archivedApplications", $archivedApplications);
			}

			// apply actual filter, handles query as well when no filters specified
			$applications = $this->applicationRepository->findByFilter($selectedContact, $selectedPosting, 0, "crdate", "DESC");

			// Set posting-selectBox content dynamically based on selected contact
			if (($selectedPosting == "" && $selectedContact != ""))
			{
				$postingsFilter = $this->postingRepoitory->findByContact(intval($selectedContact));
			}
			else
			{
				$postingsFilter = $this->postingRepoitory->findAll();
			}

			// Fetch all Contacts for select-Box
			$contactsFilter = $this->contactRepository->findAllWithOrder("last_name", "ASC");

			// Persist selection in session storage
			$sessionData["selectedPosting"] = $selectedPosting;
			$sessionData["archivedSelected"] = $archivedSelected;
			$sessionData["selectedContact"] = $selectedContact;
			$GLOBALS["BE_USER"]->setAndSaveSessionData("tx_jobs", $sessionData);

			$this->view->assign("selectedPosting", $selectedPosting);
			$this->view->assign("archivedSelected", $archivedSelected);
			$this->view->assign("selectedContact", $selectedContact);
			$this->view->assign("applications", $applications);
			$this->view->assign("postings", $postingsFilter);
			$this->view->assign("contacts", $contactsFilter);
		}

		/**
		 * action showApplication
		 *
		 * @param $application
		 *
		 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
		 */
		public function showApplicationAction(\ITX\Jobs\Domain\Model\Application $application)
		{
			// Handles archive request
			if ($this->request->hasArgument("archive"))
			{
				if ($application->isArchived())
				{
					$application->setArchived(false);
				}
				else
				{
					$application->setArchived(true);
				}
				$this->persistenceManager->update($application);
			}

			// Handles delete request
			if ($this->request->hasArgument("delete"))
			{
				$this->persistenceManager->remove($application);
				$this->redirect('listApplications', 'Backend', 'jobs');
			}

			// Handles status change request
			if ($this->request->hasArgument("status"))
			{
				$application->setStatus($this->statusRepository->findByUid($this->request->getArgument("status")));
				$this->persistenceManager->update($application);
			}

			// Fetch baseuri for f:uri to access Public folder
			$baseUri = str_replace("typo3/", "", $this->request->getBaseUri());

			// Find Followers and sort them todo: make choosable if sorted automatically or own sorting
			$selectableStatus = $this->statusRepository->findFollowers($application->getStatus()->getUid());

			$this->view->assign("contact", $contactFilter);
			$this->view->assign("archived", $archivedFilter);
			$this->view->assign("posting", $postingFilter);

			$this->view->assign("selectableStatus", $selectableStatus);
			$this->view->assign("application", $application);
			$this->view->assign("baseUri", $baseUri);
		}

		public function dashboardAction()
		{
			// Get data for counter of new applications with referenced contact
			$contact = $this->getActiveBeContact();
			$newApps = $this->applicationRepository->findNewApplicationsByContact($contact->getUid());

			$this->view->assign("newApps", count($newApps));
			$this->view->assign("contact", $contact);
		}

		public function settingsAction()
		{
			if ($this->request->hasArgument("pid"))
			{
				$pid = $this->request->getArgument("pid");
				$language = $this->request->getArgument("language");
				switch ($language)
				{
					case "de":
						break;
					case "en";
						$this->statusRepository->generateStatusEn("tx_jobs_domain_model_status.sql", "tx_jobs_domain_model_status_mm.sql", $pid);
						break;
				}
				$this->addFlashMessage("Finished!");
			}
		}

		/**
		 * Returns the Contact which has the currently logged in backend user referenced
		 *
		 * @return Contact
		 */
		private function getActiveBeContact()
		{
			$beUserUid = $GLOBALS['BE_USER']->user["uid"];

			return $this->contactRepository->findByBackendUser($beUserUid)[0];
		}
	}