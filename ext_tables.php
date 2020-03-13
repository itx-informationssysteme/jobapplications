<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {
        if (TYPO3_MODE === 'BE') {

            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'ITX.Jobapplications',
                'web', // Make module a submodule of 'web'
                'backend', // Submodule key
                '', // Position
                [
                    'Backend' => 'dashboard, listApplications, showApplication, settings'
                ],
                [
                    'access' => 'user,group',
                    'icon'   => 'EXT:jobapplications/Resources/Public/Icons/logo_jobs.svg',
                    'labels' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf',
					'navigationComponentId' => '',
					'inheritNavigationComponentFromMainModule' => false,
                ]
            );
        }

		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jobapplications_domain_model_application', 'EXT:jobapplications/Resources/Private/Language/locallang_csh_tx_jobapplications_domain_model_application.xlf');
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jobapplications_domain_model_application');

		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jobapplications_domain_model_contact', 'EXT:jobapplications/Resources/Private/Language/locallang_csh_tx_jobapplications_domain_model_contact.xlf');
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jobapplications_domain_model_contact');

		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jobapplications_domain_model_location', 'EXT:jobapplications/Resources/Private/Language/locallang_csh_tx_jobapplications_domain_model_location.xlf');
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jobapplications_domain_model_location');

		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jobapplications_domain_model_posting', 'EXT:jobapplications/Resources/Private/Language/locallang_csh_tx_jobapplications_domain_model_posting.xlf');
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jobapplications_domain_model_posting');

		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jobapplications_domain_model_status', 'EXT:jobapplications/Resources/Private/Language/locallang_csh_tx_jobapplications_domain_model_status.xlf');
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jobapplications_domain_model_status');
    }
);
