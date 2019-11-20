<?php

	namespace ITX\Jobs\Controller;

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
			$selectedPosting = "";
			$selectedContact = "";

			if ($this->request->hasArgument("posting") || $this->request->hasArgument("contact") || $this->request->hasArgument("archived"))
			{
				$selectedPosting = $this->request->getArgument("posting");
				$selectedContact = $this->request->getArgument("contact");
				$archivedSelected = $this->request->getArgument("archived");

				if ($archivedSelected)
				{
					$archivedApplications = $this->applicationRepository->findByFilter($selectedContact, $selectedPosting, 1);
					$this->view->assign("archivedApplications", $archivedApplications);
				}
				$applications = $this->applicationRepository->findByFilter($selectedContact, $selectedPosting);
				$this->view->assign("selectedPosting", $selectedPosting);
				$this->view->assign("selectedContact", $selectedContact);
				$this->view->assign("archivedSelected", $archivedSelected);
			}
			else
			{
				$applications = $this->applicationRepository->findAll();
			}

			if ($selectedPosting == "" && $selectedContact != "")
			{
				$postingsFilter = $this->postingRepoitory->findByContact(intval($selectedContact));
			}
			else
			{
				$postingsFilter = $this->postingRepoitory->findAll();
			}

			$contactsFilter = $this->contactRepository->findAllWithOrder("last_name", "ASC");

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

			if($this->request->hasArgument("delete")) {
				$this->persistenceManager->remove($application);
				$this->redirect('listApplications', 'Backend', 'jobs');
			}

			$baseUri = str_replace("typo3/", "", $this->request->getBaseUri());

			$this->view->assign("application", $application);
			$this->view->assign("baseUri", $baseUri);
		}

		public function dashboardAction() {

		}
	}