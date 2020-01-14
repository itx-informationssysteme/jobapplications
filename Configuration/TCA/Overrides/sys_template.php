<?php
	defined('TYPO3_MODE') || die();
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('jobapplications', 'Configuration/TypoScript', 'Jobapplications');

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
		'jobapplications',
		'Configuration/TypoScript/IncludeBootstrap',
		'[Optional] Include Bootstrap for working default layout'
	);
