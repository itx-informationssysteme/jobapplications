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

	$EM_CONF[$_EXTKEY] = [
		'title' => 'Jobapplications',
		'description' => 'This extension enables you to manage job postings, provides you with an application form and a backend module to manage incoming applications.',
		'category' => 'plugin',
		'author' => 'Stefanie Döll, Benjamin Jasper',
		'author_company' => 'it.x informationssysteme gmbh',
		'author_email' => 'typo-itx@itx.de',
		'state' => 'stable',
		'uploadfolder' => 1,
		'createDirs' => '',
		'clearCacheOnLoad' => 1,
		'version' => '1.0.3',
		'constraints' => [
			'depends' => [
				'typo3' => '9.5.0 - 10.4.99',
				'vhs' => '6.0.0 - 6.2.99'
			],
			'conflicts' => [],
			'suggests' => [],
		],
	];
