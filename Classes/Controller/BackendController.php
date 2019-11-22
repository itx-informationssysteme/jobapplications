<?php

	namespace ITX\Jobs\Controller;

	use ITX\Jobs\Domain\Model\Contact;
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
			// Get all filter elements and set them to empty if there are none
			$this->request->hasArgument("contact") ? $selectedContact = $this->request->getArgument("contact") : $selectedContact = "-1";
			$this->request->hasArgument("archived") ? $archivedSelected = $this->request->getArgument("archived") : $archivedSelected = "";
			$this->request->hasArgument("posting") ? $selectedPosting = $this->request->getArgument("posting") : $selectedPosting = "";
			$contact = $this->getActiveBeContact();

			// Handling a status change, triggered in listApplications View
			if ($this->request->hasArgument("status"))
			{
				$application = $this->applicationRepository->findByUid($this->request->getArgument("application"));
				$application->setStatus($this->statusRepository->findByUid($this->request->getArgument("status")));
				$this->persistenceManager->update($application);
			}

			// Select contact automatically based on user who is accessing this
			if ($contact && $selectedPosting == "" && $selectedContact != "")
			{
				$contact ? $selectedContact = $contact->getUid() : null;
			}
			else
			{
				$selectedContact = "";
			}

			// Handle show archived applications when selected in frontend-backend
			if ($archivedSelected != "")
			{
				$archivedApplications = $this->applicationRepository->findByFilter($selectedContact, $selectedPosting, 1);
				$this->view->assign("archivedApplications", $archivedApplications);
			}

			// apply actual filter, handles query as well when no filters specified
			$applications = $this->applicationRepository->findByFilter($selectedContact, $selectedPosting);

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

			// Pass through objects for filter, so when the user returns to listApplication the selections are applied as before
			$this->request->hasArgument("posting") ? $contactFilter = $this->request->getArgument("contact") : $contactFilter = "";
			$this->request->hasArgument("archived") ? $archivedFilter = $this->request->getArgument("archived") : $archivedFilter = "";
			$this->request->hasArgument("posting") ? $postingFilter = $this->request->getArgument("posting") : $postingFilter = "";

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