<?php

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

	namespace ITX\Jobapplications\Controller;

	use Psr\Http\Message\ServerRequestInterface;
    use TYPO3\CMS\Backend\Attribute\Controller;
    use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
    use TYPO3\CMS\Core\Http\RedirectResponse;
    use TYPO3\CMS\Core\Pagination\SimplePagination;
    use TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException;
    use TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException;
    use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
	use ITX\Jobapplications\Domain\Model\Application;
	use Psr\Http\Message\ResponseInterface;
	use ITX\Jobapplications\Domain\Repository\ApplicationRepository;
	use ITX\Jobapplications\Domain\Repository\PostingRepository;
	use ITX\Jobapplications\Domain\Repository\ContactRepository;
	use ITX\Jobapplications\Domain\Repository\StatusRepository;
	use TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException;
	use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
	use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
	use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
	use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
	use ITX\Jobapplications\Service\ApplicationFileService;
	use ITX\Jobapplications\Domain\Model\Contact;
	use ITX\Jobapplications\Domain\Model\Posting;
	use ITX\Jobapplications\Domain\Model\Status;
	use ITX\Jobapplications\Utility\GoogleIndexingApiConnector;
	use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
	use TYPO3\CMS\Core\Messaging\FlashMessage;
	use TYPO3\CMS\Core\Resource\Exception\InsufficientUserPermissionsException;

	/**
	 * Class BackendController
	 *
	 * @package ITX\Jobapplications\Controller
	 */
    #[Controller]
	class BackendController extends ActionController
	{
		/**
		 * applicationRepository
		 *
		 * @var \ITX\Jobapplications\Domain\Repository\ApplicationRepository
		 */
		protected $applicationRepository = null;

		/**
		 * postingRepository
		 *
		 * @var \ITX\Jobapplications\Domain\Repository\PostingRepository
		 */
		protected $postingRepository = null;

		/**
		 * contactRepository
		 *
		 * @var \ITX\Jobapplications\Domain\Repository\ContactRepository
		 */
		protected $contactRepository = null;

		/**
		 * statusRepository
		 *
		 * @var \ITX\Jobapplications\Domain\Repository\StatusRepository
		 */
		protected $statusRepository = null;

		/**
		 * persistenceManager
		 *
		 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
		 */
		protected $persistenceManager;

		/**
		 * @var \ITX\Jobapplications\Service\ApplicationFileService
		 */
		protected $applicationFileService;

		protected GoogleIndexingApiConnector $connector;

		public function __construct(GoogleIndexingApiConnector $connector, protected readonly ModuleTemplateFactory $moduleTemplateFactory,)
		{
			$this->connector = $connector;
		}

		/**
		 * action listApplications
		 *
		 * @throws NoSuchArgumentException
		 * @throws UnknownObjectException
		 * @throws IllegalObjectTypeException
		 */
		public function listApplicationsAction(): ResponseInterface
		{
            $moduleTemplate = $this->moduleTemplateFactory->create($this->request);

			$page = $this->request->hasArgument('page') ? (int)$this->request->getArgument('page') : 1;
			$itemsPerPage = 12;

			$sessionData = $GLOBALS['BE_USER']->getSessionData('tx_jobapplications');
			$contact = $this->getActiveBeContact();

			$dailyLogin = $sessionData['dailyLogin'] ?? null;
			if ((empty($dailyLogin) && $contact instanceof Contact) || ($dailyLogin === false && $contact instanceof Contact))
			{
				return $this->redirect('dashboard');
			}

			// Get all filter elements and set them to empty if there are none and use session storage for persisting selection
			if ($this->request->hasArgument('submit'))
			{
				$this->request->hasArgument('contact') ? $selectedContact = intval($this->request->getArgument('contact')) : $selectedContact = 0;
				$this->request->hasArgument('archived') ? $archivedSelected = intval($this->request->getArgument('archived')) : $archivedSelected = 0;
				$this->request->hasArgument('posting') ? $selectedPosting = intval($this->request->getArgument('posting')) : $selectedPosting = 0;
				$this->request->hasArgument('status') ? $selectedStatus = intval($this->request->getArgument('status')) : $selectedStatus = 0;
			}
			else
			{
				$selectedPosting = $sessionData['selectedPosting'] ?? null;
				$archivedSelected = $sessionData['archivedSelected'] ?? null;
				$selectedContact = $sessionData['selectedContact'] ?? null;
				$selectedStatus = $sessionData['selectedStatus'] ?? null;
			}

			// Handling a status change, triggered in listApplications View
			if ($this->request->hasArgument('statusChange'))
			{
				$application = $this->applicationRepository->findByUid($this->request->getArgument('application'));
				$application->setStatus($this->statusRepository->findByUid($this->request->getArgument('statusChange')));
				$this->applicationRepository->update($application);
				$this->persistenceManager->persistAll();
			}

			// Select contact automatically based on user who is accessing this
			if ($contact instanceof Contact && $selectedContact == -1)
			{
				$selectedContact = $contact->getUid();
			}

			// Handle show archived applications when selected in frontend-backend. If archived not selected show all applications which are not archived.
			if ($archivedSelected != 0)
			{
				// apply actual filter
				$applications = $this->applicationRepository->findByFilter($selectedContact, $selectedPosting, $selectedStatus, 1);
			}
			else
			{
				// apply actual filter, handles query as well when no filters specified
				$applications = $this->applicationRepository->findByFilter($selectedContact, $selectedPosting, $selectedStatus, 0, 'crdate', 'DESC');
			}

			// Set posting-selectBox content dynamically based on selected contact empty($selectedPosting)
			if ($selectedPosting === null && $selectedContact !== null)
			{
				$postingsFilter = $this->postingRepository->findByContact($selectedContact);
			}
			else
			{
				$postingsFilter = $this->postingRepository->findAllWithOrderIgnoreEnable();
			}

			// Fetch all Contacts and Statuses for select-Box
			$contactsFilter = $this->contactRepository->findAllWithOrder('last_name', 'ASC');
			$statusesFilter = $this->statusRepository->findAllWithOrder();

			// Persist selection in session storage
			$sessionData['selectedPosting'] = $selectedPosting;
			$sessionData['archivedSelected'] = $archivedSelected;
			$sessionData['selectedContact'] = $selectedContact;
			$sessionData['selectedStatus'] = $selectedStatus;
			$GLOBALS['BE_USER']->setAndSaveSessionData('tx_jobapplications', $sessionData);

			$paginator = new QueryResultPaginator($applications, $page, $itemsPerPage);

			$pagination = new SimplePagination($paginator);

			$this->view->assign('paginator', $paginator);
			$this->view->assign('pagination', $pagination);
			$this->view->assign('applications', $paginator->getPaginatedItems());

			$this->view->assign('selectedPosting', $selectedPosting);
			$this->view->assign('archivedSelected', $archivedSelected);
			$this->view->assign('selectedContact', $selectedContact);
			$this->view->assign('selectedStatus', $selectedStatus);
			$this->view->assign('postings', $postingsFilter);
			$this->view->assign('contacts', $contactsFilter);
			$this->view->assign('statuses', $statusesFilter);

            $moduleTemplate->setContent($this->view->render());
            return $this->htmlResponse($moduleTemplate->renderContent());
		}

		/**
		 * Returns the Contact which has the currently logged in backend user referenced
		 *
		 * @return Contact
		 */
		private function getActiveBeContact()
		{
			$beUserUid = $GLOBALS['BE_USER']->user['uid'];

			return $this->contactRepository->findByBackendUser($beUserUid)[0];
		}

        /**
         * action showApplication
         *
         * @param Application $application
         *
         * @return ResponseInterface
         * @throws IllegalObjectTypeException
         * @throws UnknownObjectException
         * @throws InsufficientFolderAccessPermissionsException
         * @throws InvalidFileNameException
         */
		public function showApplicationAction(Application $application): ResponseInterface
		{
            $moduleTemplate = $this->moduleTemplateFactory->create($this->request);

			$statusDatabaseOp = false;

			// Handles archive request
			if ($this->request->hasArgument('archive'))
			{
				if ($application->isArchived())
				{
					$application->setArchived(false);
				}
				else
				{
					$application->setArchived(true);
				}
				$this->applicationRepository->update($application);
				$statusDatabaseOp = true;
			}

			// Handles delete request
			if ($this->request->hasArgument('delete'))
			{
				$fileStorage = (int)$this->settings['fileStorage'];

				$this->applicationRepository->remove($application);
				$this->applicationFileService->deleteApplicationFolder($this->applicationFileService->getApplicantFolder($application), $fileStorage);
				$this->persistenceManager->persistAll();
				$this->redirect('listApplications', 'Backend', 'jobapplications');
			}

			// Handles status change request
			if ($this->request->hasArgument('status'))
			{
				/* @var Status $newStatus */
				$newStatus = $this->statusRepository->findByUid($this->request->getArgument('status'));
				$application->setStatus($newStatus);
				$this->applicationRepository->update($application);
				$statusDatabaseOp = true;
			}

			// If we made a databse operation we want to make sure its commited instantly
			if ($statusDatabaseOp)
			{
				$this->persistenceManager->persistAll();
			}

			$this->view->assign('application', $application);

            $moduleTemplate->setContent($this->view->render());
            return $this->htmlResponse($moduleTemplate->renderContent());
		}

		/**
		 * Action for Backend Dashboard
		 *
		 */
		public function dashboardAction(): ResponseInterface
		{
            $moduleTemplate = $this->moduleTemplateFactory->create($this->request);

			// Get data for counter of new applications with referenced contact
			$contact = $this->getActiveBeContact();

			/** @var BackendUserAuthentication $backendUser */
			$backendUser = $GLOBALS['BE_USER'];

			$session = $backendUser->getSessionData('tx_jobapplications') ?? [];

			if ((isset($session['dailyLogin']) && $contact instanceof Contact) || (!isset($session['dailyLogin']) && $contact instanceof Contact))
			{
				$session['dailyLogin'] = true;
				$backendUser->setAndSaveSessionData('tx_jobapplications', $session);
			}

			if ($contact)
			{
				$newApps = $this->applicationRepository->findNewApplicationsByContact($contact->getUid());
			}
			else
			{
				$newApps = [];
			}

			$this->view->assign('admin', $GLOBALS['BE_USER']->isAdmin());
			$this->view->assign('newApps', count($newApps));
			$this->view->assign('contact', $contact);

            $moduleTemplate->setContent($this->view->render());
            return $this->htmlResponse($moduleTemplate->renderContent());
		}

		/**
		 * Action for settings page
		 *
		 * @throws InsufficientUserPermissionsException
		 * @throws NoSuchArgumentException
		 * @throws \Exception
		 */
		public function settingsAction(): ResponseInterface
		{
            $moduleTemplate = $this->moduleTemplateFactory->create($this->request);

			if (!$GLOBALS['BE_USER']->isAdmin())
			{
				throw new InsufficientUserPermissionsException('Insufficient permissions');
			}

			if ($this->request->hasArgument('pid'))
			{
				$pid = $this->request->getArgument('pid');
				$language = $this->request->getArgument('language');
				$langUid = $this->statusRepository->findLangUid($language);

				$this->statusRepository->generateStatus('tx_jobapplications_domain_model_status_'.$language.'.sql', 'tx_jobapplications_domain_model_status_mm.sql', $pid, $langUid);

				$this->addFlashMessage('Finished!');
			}

			if ($this->request->hasArgument('batch_index'))
			{
				$postings = $this->postingRepository->findAllIncludingHiddenAndDeleted();

				$removeCounter = 0;
				$updateCounter = 0;
				$error_bit = false;

				/** @var Posting $posting */
				foreach ($postings as $posting)
				{
					if ($posting->isHidden() || $posting->isDeleted())
					{
						if (!$this->connector->updateGoogleIndex($posting->getUid(), true, $posting))
						{
							$error_bit = true;
						}
						else
						{
							$removeCounter++;
						}
					}
					else if (!$this->connector->updateGoogleIndex($posting->getUid(), false, $posting))
					{
						$error_bit = true;
					}
					else
					{
						$updateCounter++;
					}
				}
				if ($error_bit)
				{
					$this->addFlashMessage('Not successful updated/added '.$updateCounter.' and removed '.$removeCounter.' postings', '', FlashMessage::ERROR);
				}
				else
				{
					$this->addFlashMessage('Done with updating/adding '.$updateCounter.' and removing '.$removeCounter.' postings');
				}

			}

			$this->view->assign('admin', $GLOBALS['BE_USER']->isAdmin());

            $moduleTemplate->setContent($this->view->render());
            return $this->htmlResponse($moduleTemplate->renderContent());
		}

		public function injectApplicationRepository(ApplicationRepository $applicationRepository): void
		{
			$this->applicationRepository = $applicationRepository;
		}

		public function injectPostingRepository(PostingRepository $postingRepository): void
		{
			$this->postingRepository = $postingRepository;
		}

		public function injectContactRepository(ContactRepository $contactRepository): void
		{
			$this->contactRepository = $contactRepository;
		}

		public function injectStatusRepository(StatusRepository $statusRepository): void
		{
			$this->statusRepository = $statusRepository;
		}

		public function injectPersistenceManager(PersistenceManager $persistenceManager): void
		{
			$this->persistenceManager = $persistenceManager;
		}

		public function injectApplicationFileService(ApplicationFileService $applicationFileService): void
		{
			$this->applicationFileService = $applicationFileService;
		}
	}
