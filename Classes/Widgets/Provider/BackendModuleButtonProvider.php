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

	namespace ITX\Jobapplications\Widgets\Provider;

	use TYPO3\CMS\Dashboard\Widgets\Provider\ButtonProvider;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException;
	use TYPO3\CMS\Backend\Routing\UriBuilder;

	/**
	 * Class BackendModuleButtonProvider
	 *
	 * @package ITX\Jobapplications\Widgets\Provider
	 */
	class BackendModuleButtonProvider extends ButtonProvider
	{
		/**
		 * BackendModuleButtonProvider constructor.
		 *
		 * @param string $target
		 */
		public function __construct(string $target = '')
		{
			if (!$GLOBALS['BE_USER']->check('modules', 'web_JobapplicationsBackend'))
			{
				return;
			}

			/** @var UriBuilder $uriBuilder */
			$uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);

			try
			{
				$buttonLink = $uriBuilder->buildUriFromRoute('jobapplications_backend');
			}
			catch (RouteNotFoundException $e)
			{
				$buttonLink = null;
			}

			$buttonText = 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:be.widget.applications_per_posting.button';

			parent::__construct($buttonText, $buttonLink, $target);
		}
	}
