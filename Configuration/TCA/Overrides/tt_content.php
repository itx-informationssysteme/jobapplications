<?php
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
		'ITX.Jobs',
		'Frontend',
		'LLL:EXT:jobs/Resources/Private/Language/locallang_backend.xlf:tx_jobs_frontend.title'
	);

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
		'ITX.Jobs',
		'DetailView',
		'LLL:EXT:jobs/Resources/Private/Language/locallang_backend.xlf:tx_jobs_detailview.title'
	);

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
		'ITX.Jobs',
		'ApplicationForm',
		'LLL:EXT:jobs/Resources/Private/Language/locallang_backend.xlf:tx_jobs_applicationform.title'
	);

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
		'ITX.Jobs',
		'ContactDisplay',
		'LLL:EXT:jobs/Resources/Private/Language/locallang_backend.xlf:tx_jobs_contactdisplay.title'
	);

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

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tt_content.pi_flexform.jobs_applicationform.list', 'EXT:jobs/Resources/Private/Language/locallang_csh_flexform.xlf');