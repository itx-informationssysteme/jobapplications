<?php
	/***************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2019
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

	namespace ITX\Jobapplications\Widgets;

	/**
	 * Class PostingsActive
	 *
	 * @package ITX\Jobapplications\Widgets
	 */
	class PostingsActive extends \TYPO3\CMS\Dashboard\Widgets\AbstractNumberWithIconWidget
	{
		/** @var string */
		protected $title = 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:be.widget.postings_active.title';

		/** @var int */
		protected $number = 0;

		/** @var string */
		protected $subtitle = 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:be.widget.postings_active.subtitle';

		/** @var string  */
		protected $description = 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:be.widget.postings_active.description';

		/** @var string */
		protected $icon = 'content-carousel-item-calltoaction';

		protected function initializeView(): void
		{
			/** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectmanager */
			$objectmanager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

			/** @var \ITX\Jobapplications\Domain\Repository\PostingRepository $postingRepo */
			$postingRepo = $objectmanager->get(\ITX\Jobapplications\Domain\Repository\PostingRepository::class);

			$numberOfPosting = $postingRepo->findAllIgnoreStoragePage()->count();

			$this->number = $numberOfPosting;
			parent::initializeView();
		}
	}