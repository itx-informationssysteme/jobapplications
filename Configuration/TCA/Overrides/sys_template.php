<?php
	defined('TYPO3_MODE') || die();
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('jobapplications', 'Configuration/TypoScript', 'Jobapplications');

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
		'jobapplications',
		'Configuration/TypoScript/IncludeBootstrap',
		'Jobapplications: [Optional] Include Bootstrap for working default layout'
	);

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
		'jobapplications',
		'Configuration/TypoScript/IncludeJQuery',
		'Jobapplications: [Optional] Include jQuery (if necessary) for working filters'
	);
