<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'ITX.Jobs',
            'Frontend',
            'Jobs'
        );

		\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
			'ITX.Jobs',
			'DetailView',
			'Jobs DetailView'
		);

		\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
			'ITX.Jobs',
			'ApplicationForm',
			'Jobs Application Form'
		);

		\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
			'ITX.Jobs',
			'ContactDisplay',
			'Jobs Contact Display'
		);

        if (TYPO3_MODE === 'BE') {

            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'ITX.Jobs',
                'web', // Make module a submodule of 'web'
                'backend', // Submodule key
                '', // Position
                [
                    'Posting' => 'list, show, ','Application' => 'new, create',
                ],
                [
                    'access' => 'user,group',
                    'icon'   => 'EXT:jobs/Resources/Public/Icons/logo_jobs.svg',
                    'labels' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_backend.xlf',
                ]
            );

        }

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('jobs', 'Configuration/TypoScript', 'Jobs');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jobs_domain_model_posting', 'EXT:jobs/Resources/Private/Language/locallang_csh_tx_jobs_domain_model_posting.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jobs_domain_model_posting');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jobs_domain_model_contact', 'EXT:jobs/Resources/Private/Language/locallang_csh_tx_jobs_domain_model_contact.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jobs_domain_model_contact');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jobs_domain_model_location', 'EXT:jobs/Resources/Private/Language/locallang_csh_tx_jobs_domain_model_location.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jobs_domain_model_location');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jobs_domain_model_application', 'EXT:jobs/Resources/Private/Language/locallang_csh_tx_jobs_domain_model_application.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jobs_domain_model_application');

    }
);
