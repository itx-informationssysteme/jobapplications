<?php

declare(strict_types=1);

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

$flexForms = [
    'jobapplications_frontend' => 'FILE:EXT:jobapplications/Configuration/FlexForms/frontend.xml',
    'jobapplications_detailview' => 'FILE:EXT:jobapplications/Configuration/FlexForms/detailview.xml',
    'jobapplications_applicationform' => 'FILE:EXT:jobapplications/Configuration/FlexForms/applicationform.xml',
    'jobapplications_contactdisplay' => 'FILE:EXT:jobapplications/Configuration/FlexForms/contactdisplay.xml',
    'jobapplications_successpage' => 'FILE:EXT:jobapplications/Configuration/FlexForms/successpage.xml',
];

ExtensionUtility::registerPlugin(
    'Jobapplications',
    'Frontend',
    'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:tx_jobapplications_frontend.title',
    'jobapplications-plugin-icon',
    'Jobapplications',
    ''
);
ExtensionUtility::registerPlugin(
    'Jobapplications',
    'DetailView',
    'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:tx_jobapplications_detailview.title',
    'jobapplications-plugin-icon',
    'Jobapplications',
    ''
);
ExtensionUtility::registerPlugin(
    'Jobapplications',
    'ApplicationForm',
    'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:tx_jobapplications_applicationform.title',
    'jobapplications-plugin-icon',
    'Jobapplications',
    ''
);
ExtensionUtility::registerPlugin(
    'Jobapplications',
    'ContactDisplay',
    'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:tx_jobapplications_contactdisplay.title',
    'jobapplications-plugin-icon',
    'Jobapplications',
    ''
);
ExtensionUtility::registerPlugin(
    'Jobapplications',
    'SuccessPage',
    'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:tx_jobapplications_successpage.title',
    'jobapplications-plugin-icon',
    'Jobapplications',
    ''
);

foreach ($flexForms as $pluginSignature => $flexFormPath) {
    ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, $flexFormPath, 'list');
    ExtensionManagementUtility::addPiFlexFormValue('*', $flexFormPath, $pluginSignature);
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
    ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'pi_flexform', $pluginSignature, 'after:subheader');
}

