<?php

	namespace ITX\Jobapplications\Controller;

    use ITX\Jobapplications\Domain\Repository\ContactRepository;
	use Psr\Http\Message\ResponseInterface;
	use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
    use TYPO3Fluid\Fluid\View\ViewInterface;

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
	class ContactController extends ActionController
	{
		protected ContactRepository $contactRepository;

		/**
		 * Initializes the view before invoking an action method.
		 *
		 * Override this method to solve assign variables common for all actions
		 * or prepare the view in another way before the action is called.
		 *
		 * @param ViewInterface $view The view to be initialized
		 */
		public function initializeView(ViewInterface $view): void
        {
			if (is_object($GLOBALS['TSFE']))
			{
				$view->assign('pageData', $GLOBALS['TSFE']->page);
			}
		}

		/**
		 * action list
		 *
		 * @return ResponseInterface
		 */
		public function listAction(): ResponseInterface
		{
			$contacts = [];
			$selectedContactsStr = $this->settings["selectedContacts"];
			if (!empty($selectedContactsStr))
			{
				$contacts = explode(",", $selectedContactsStr);
				$contactObjects = $this->contactRepository->findMultipleByUid($contacts);

				$sortedMap = [];
				foreach ($contacts as $contact) {
					$sortedMap[$contact] = null;
				}

				foreach ($contactObjects as $contactObject) {
					$sortedMap[$contactObject->getUid()] = $contactObject;
				}

				$contactObjects = array_values($sortedMap);
			}
			else
			{
				$contactObjects = $this->contactRepository->findAll();
			}

			$this->view->assign('contacts', $contactObjects);

			return $this->htmlResponse();
		}

		public function injectContactRepository(ContactRepository $contactRepository): void
		{
			$this->contactRepository = $contactRepository;
		}
	}
