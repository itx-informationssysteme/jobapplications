<?php
	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['jobs_frontend'] = 'pi_flexform';
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	// plugin signature: <extension key without underscores> '_' <plugin name in lowercase>
		'jobs_frontend',
		// Flexform configuration schema file
		'FILE:EXT:jobs/Configuration/FlexForms/frontend.xml'
	);

	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['jobs_detailview'] = 'pi_flexform';
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	// plugin signature: <extension key without underscores> '_' <plugin name in lowercase>
		'jobs_detailview',
		// Flexform configuration schema file
		'FILE:EXT:jobs/Configuration/FlexForms/detailview.xml'
	);

	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['jobs_applicationform'] = 'pi_flexform';
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	// plugin signature: <extension key without underscores> '_' <plugin name in lowercase>
		'jobs_applicationform',
		// Flexform configuration schema file
		'FILE:EXT:jobs/Configuration/FlexForms/applicationform.xml'
	);

	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['jobs_contactdisplay'] = 'pi_flexform';
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	// plugin signature: <extension key without underscores> '_' <plugin name in lowercase>
		'jobs_contactdisplay',
		// Flexform configuration schema file
		'FILE:EXT:jobs/Configuration/FlexForms/contactdisplay.xml'
	);
