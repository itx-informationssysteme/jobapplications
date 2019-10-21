<?php
	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['jobs_frontend'] = 'pi_flexform';
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	// plugin signature: <extension key without underscores> '_' <plugin name in lowercase>
		'jobs_frontend',
		// Flexform configuration schema file
		'FILE:EXT:jobs/Configuration/FlexForms/frontend.xml'
	);
