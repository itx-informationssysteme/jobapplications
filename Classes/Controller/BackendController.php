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

	use ITX\Jobapplications\Domain\Model\Contact;
	use ITX\Jobapplications\Domain\Model\Posting;
	use ITX\Jobapplications\Domain\Model\Status;
	use ITX\Jobapplications\Utility\GoogleIndexingApiConnector;
	use TYPO3\CMS\Core\Messaging\FlashMessage;
	use TYPO3\CMS\Core\Resource\Exception\InsufficientUserPermissionsException;
	use TYPO3\CMS\Core\Utility\DebugUtility;
	use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

	/**
	 * Class BackendController
	 *
	 * @package ITX\Jobapplications\Controller
	 */
	class BackendController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
	{
		/**
		 * applicationRepository
		 *
		 * @var \ITX\Jobapplications\Domain\Repository\ApplicationRepository
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		protected $applicationRepository = null;

		/**
		 * postingRepository
		 *
		 * @var \ITX\Jobapplications\Domain\Repository\PostingRepository
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		protected $postingRepository = null;

		/**
		 * contactRepository
		 *
		 * @var \ITX\Jobapplications\Domain\Repository\ContactRepository
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		protected $contactRepository = null;

		/**
		 * statusRepository
		 *
		 * @var \ITX\Jobapplications\Domain\Repository\StatusRepository
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		protected $statusRepository = null;

		/**
		 * persistenceManager
		 *
		 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		protected $persistenceManager;

		/**
		 * @var \ITX\Jobapplications\Service\ApplicationFileService
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		protected $applicationFileService;

		/**
		 * action listApplications
		 *
		 * @return void
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
		 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
		 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
		 */
		public function listApplicationsAction()
		{
			$sessionData = $GLOBALS["BE_USER"]->getSessionData("tx_jobapplications");

			// Get all filter elements and set them to empty if there are none and use session storage for persisting selection
			if ($this->request->hasArgument("submit"))
			{
				$this->request->hasArgument("contact") ? $selectedContact = intval($this->request->getArgument("contact")) : $selectedContact = 0;
				$this->request->hasArgument("archived") ? $archivedSelected = intval($this->request->getArgument("archived")) : $archivedSelected = 0;
				$this->request->hasArgument("posting") ? $selectedPosting = intval($this->request->getArgument("posting")) : $selectedPosting = 0;
				$this->request->hasArgument("status") ? $selectedStatus = intval($this->request->getArgument("status")) : $selectedStatus = 0;
			}
			else
			{
				$selectedPosting = $sessionData["selectedPosting"];
				$archivedSelected = $sessionData["archivedSelected"];
				$selectedContact = $sessionData["selectedContact"];
				$selectedStatus = $sessionData["selectedStatus"];
			}

			$contact = $this->getActiveBeContact();

			// Handling a status change, triggered in listApplications View
			if ($this->request->hasArgument("statusChange"))
			{
				$application = $this->applicationRepository->findByUid($this->request->getArgument("application"));
				$application->setStatus($this->statusRepository->findByUid($this->request->getArgument("statusChange")));
				$this->applicationRepository->update($application);
				$this->persistenceManager->persistAll();
			}

			// Select contact automatically based on user who is accessing this
			if ($contact instanceof Contact && $selectedContact == -1)
			{
				$selectedContact = $contact->getUid();
			}

			// Handle show archived applications when selected in frontend-backend
			if ($archivedSelected != 0)
			{
				$archivedApplications = $this->applicationRepository->findByFilter($selectedContact, $selectedPosting, $selectedStatus, 1);
				$this->view->assign("archivedApplications", $archivedApplications);
			}

			// apply actual filter, handles query as well when no filters specified
			$applications = $this->applicationRepository->findByFilter($selectedContact, $selectedPosting, $selectedStatus, 0, "crdate", "DESC");

			// Set posting-selectBox content dynamically based on selected contact
			if ((empty($selectedPosting) && !empty($selectedContact)))
			{
				$postingsFilter = $this->postingRepository->findByContact(intval($selectedContact));
			}
			else
			{
				$postingsFilter = $this->postingRepository->findAllWithOrderIgnoreEnable();
			}

			// Fetch all Contacts and Statuses for select-Box
			$contactsFilter = $this->contactRepository->findAllWithOrder("last_name", "ASC");
			$statusesFilter = $this->statusRepository->findAllWithOrder();

			// Persist selection in session storage
			$sessionData["selectedPosting"] = $selectedPosting;
			$sessionData["archivedSelected"] = $archivedSelected;
			$sessionData["selectedContact"] = $selectedContact;
			$sessionData["selectedStatus"] = $selectedStatus;
			$GLOBALS["BE_USER"]->setAndSaveSessionData("tx_jobapplications", $sessionData);

			$this->view->assign("selectedPosting", $selectedPosting);
			$this->view->assign("archivedSelected", $archivedSelected);
			$this->view->assign("selectedContact", $selectedContact);
			$this->view->assign("selectedStatus", $selectedStatus);
			$this->view->assign("applications", $applications);
			$this->view->assign("postings", $postingsFilter);
			$this->view->assign("contacts", $contactsFilter);
			$this->view->assign("statuses", $statusesFilter);
		}

		/**
		 * action showApplication
		 *
		 * @param \ITX\Jobapplications\Domain\Model\Application $application
		 *
		 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
		 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
		 */
		public function showApplicationAction(\ITX\Jobapplications\Domain\Model\Application $application)
		{
			$statusDatabaseOp = false;

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
				$this->applicationRepository->update($application);
				$statusDatabaseOp = true;
			}

			// Handles delete request
			if ($this->request->hasArgument("delete"))
			{
				$this->applicationRepository->remove($application);
				$this->applicationFileService->deleteApplicationFolder($this->applicationFileService->getApplicantFolder($application));
				$this->persistenceManager->persistAll();
				$this->redirect('listApplications', 'Backend', 'jobapplications');
			}

			// Handles status change request
			if ($this->request->hasArgument("status"))
			{
				/* @var Status $newStatus */
				$newStatus = $this->statusRepository->findByUid($this->request->getArgument("status"));
				$application->setStatus($newStatus);
				$this->applicationRepository->update($application);
				$statusDatabaseOp = true;
			}

			// If we made a databse operation we want to make sure its commited instantly
			if ($statusDatabaseOp)
			{
				$this->persistenceManager->persistAll();
			}

			// Fetch baseuri for f:uri to access Public folder
			$baseUri = str_replace("typo3/", "", $this->request->getBaseUri());

			$this->view->assign("application", $application);
			$this->view->assign("baseUri", $baseUri);
		}

		/**
		 * Action for Backend Dashboard
		 *
		 */
		public function dashboardAction()
		{
			// Get data for counter of new applications with referenced contact
			$contact = $this->getActiveBeContact();

			if ($contact)
			{
				$newApps = $this->applicationRepository->findNewApplicationsByContact($contact->getUid());
			}
			else
			{
				$newApps = array();
			}

			$this->view->assign("admin", $GLOBALS['BE_USER']->isAdmin());
			$this->view->assign("newApps", count($newApps));
			$this->view->assign("contact", $contact);
		}

		/**
		 * Action for settings page
		 *
		 * @throws InsufficientUserPermissionsException
		 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
		 * @throws \Exception
		 */
		public function settingsAction()
		{
			if (!$GLOBALS['BE_USER']->isAdmin())
			{
				throw new InsufficientUserPermissionsException("Insufficient permissions");
			}

			if ($this->request->hasArgument("pid"))
			{
				$pid = $this->request->getArgument("pid");
				$language = $this->request->getArgument("language");
				$langUid = $this->statusRepository->findLangUid($language);
				$this->statusRepository->generateStatus("tx_jobapplications_domain_model_status_".$language.".sql", "tx_jobapplications_domain_model_status_mm.sql", $pid, $langUid);

				$this->addFlashMessage("Finished!");
			}

			if ($this->request->hasArgument("batch_index"))
			{
				$connector = new GoogleIndexingApiConnector(true);
				$postings = $this->postingRepository->findAllIncludingHiddenAndDeleted();

				$removeCounter = 0;
				$updateCounter = 0;
				$error_bit = false;

				/** @var Posting $posting */
				foreach ($postings as $posting)
				{
					if ($posting->isHidden() || $posting->isDeleted())
					{
						if (!$connector->updateGoogleIndex($posting->getUid(), true, $posting))
						{
							$error_bit = true;
						}
						else
						{
							$removeCounter++;
						}
					}
					else
					{
						if (!$connector->updateGoogleIndex($posting->getUid(), false, $posting))
						{
							$error_bit = true;
						}
						else
						{
							$updateCounter++;
						}

					}
				}
				if($error_bit) {
					$this->addFlashMessage("Not successful updated/added ".$updateCounter." and removed ".$removeCounter." postings", '', FlashMessage::ERROR);
				} else {
					$this->addFlashMessage("Done with updating/adding ".$updateCounter." and removing ".$removeCounter." postings");
				}

			}

			$this->view->assign("admin", $GLOBALS['BE_USER']->isAdmin());
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