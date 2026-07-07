<?php

declare(strict_types=1);

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

ExtensionUtility::registerPlugin(
    'Jobapplications',
    'Frontend',
    'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:tx_jobapplications_frontend.title',
    'jobapplications-plugin-icon',
    'Jobapplications',
    '',
    'FILE:EXT:jobapplications/Configuration/FlexForms/frontend.xml'
);
ExtensionUtility::registerPlugin(
    'Jobapplications',
    'DetailView',
    'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:tx_jobapplications_detailview.title',
    'jobapplications-plugin-icon',
    'Jobapplications',
    '',
    'FILE:EXT:jobapplications/Configuration/FlexForms/detailview.xml'
);
ExtensionUtility::registerPlugin(
    'Jobapplications',
    'ApplicationForm',
    'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:tx_jobapplications_applicationform.title',
    'jobapplications-plugin-icon',
    'Jobapplications',
    '',
    'FILE:EXT:jobapplications/Configuration/FlexForms/applicationform.xml'
);
ExtensionUtility::registerPlugin(
    'Jobapplications',
    'ContactDisplay',
    'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:tx_jobapplications_contactdisplay.title',
    'jobapplications-plugin-icon',
    'Jobapplications',
    '',
    'FILE:EXT:jobapplications/Configuration/FlexForms/contactdisplay.xml'
);
ExtensionUtility::registerPlugin(
    'Jobapplications',
    'SuccessPage',
    'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:tx_jobapplications_successpage.title',
    'jobapplications-plugin-icon',
    'Jobapplications',
    '',
    'FILE:EXT:jobapplications/Configuration/FlexForms/successpage.xml'
);

