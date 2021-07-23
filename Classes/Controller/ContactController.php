<?php

	namespace ITX\Jobapplications\Controller;

	use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

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
	 * ContactController
	 */
	class ContactController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
	{

		/**
		 * contactRepository
		 *
		 * @var \ITX\Jobapplications\Domain\Repository\ContactRepository
		 * @TYPO3\CMS\Extbase\Annotation\Inject
		 */
		protected $contactRepository = null;

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
		 * action list
		 *
		 * @param ITX\Jobapplications\Domain\Model\Contact
		 *
		 * @return void
		 */
		public function listAction()
		{
			$contacts = [];
			$selectedContactsStr = $this->settings["selectedContacts"];
			if (!empty($selectedContactsStr))
			{
				$contacts = explode(",", $selectedContactsStr);
			}
			$contactObjects = $this->contactRepository->findMultipleByUid($contacts);

			$contactByUid = [];

			foreach ($contactObjects as $contact)
			{
				$contactByUid[$contact->getLocalizedUid()] = $contact;
			}

			$contactObjects = array_replace(array_flip($contacts), $contactByUid);

			$this->view->assign('contacts', $contactObjects);
		}
	}
