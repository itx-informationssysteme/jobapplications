<?php
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
		'ITX.Jobapplications',
		'Frontend',
		'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:tx_jobapplications_frontend.title'
	);

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
		'ITX.Jobapplications',
		'DetailView',
		'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:tx_jobapplications_detailview.title'
	);

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
		'ITX.Jobapplications',
		'ApplicationForm',
		'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:tx_jobapplications_applicationform.title'
	);

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
		'ITX.Jobapplications',
		'ContactDisplay',
		'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:tx_jobapplications_contactdisplay.title'
	);

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
		'ITX.Jobapplications',
		'SuccessPage',
		'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:tx_jobapplications_successpage.title'
	);

	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['jobapplications_frontend'] = 'pi_flexform';
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	// plugin signature: <extension key without underscores> '_' <plugin name in lowercase>
		'jobapplications_frontend',
		// Flexform configuration schema file
		'FILE:EXT:jobapplications/Configuration/FlexForms/frontend.xml'
	);

	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['jobapplications_detailview'] = 'pi_flexform';
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	// plugin signature: <extension key without underscores> '_' <plugin name in lowercase>
		'jobapplications_detailview',
		// Flexform configuration schema file
		'FILE:EXT:jobapplications/Configuration/FlexForms/detailview.xml'
	);

	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['jobapplications_applicationform'] = 'pi_flexform';
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	// plugin signature: <extension key without underscores> '_' <plugin name in lowercase>
		'jobapplications_applicationform',
		// Flexform configuration schema file
		'FILE:EXT:jobapplications/Configuration/FlexForms/applicationform.xml'
	);

	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['jobapplications_contactdisplay'] = 'pi_flexform';
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	// plugin signature: <extension key without underscores> '_' <plugin name in lowercase>
		'jobapplications_contactdisplay',
		// Flexform configuration schema file
		'FILE:EXT:jobapplications/Configuration/FlexForms/contactdisplay.xml'
	);

	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['jobapplications_successpage'] = 'pi_flexform';
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	// plugin signature: <extension key without underscores> '_' <plugin name in lowercase>
		'jobapplications_successpage',
		// Flexform configuration schema file
		'FILE:EXT:jobapplications/Configuration/FlexForms/successpage.xml'
	);

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tt_content.pi_flexform.jobapplications_applicationform.list', 'EXT:jobapplications/Resources/Private/Language/locallang_csh_flexform.xlf');