<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {
        if (TYPO3_MODE === 'BE') {

            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'ITX.Jobs',
                'web', // Make module a submodule of 'web'
                'backend', // Submodule key
                '', // Position
                [
                    'Backend' => 'dashboard, listApplications, showApplication, settings'
                ],
                [
                    'access' => 'user,group',
                    'icon'   => 'EXT:jobs/Resources/Public/Icons/logo_jobs.svg',
                    'labels' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_backend.xlf',
                ]
            );
        }
    }
);
