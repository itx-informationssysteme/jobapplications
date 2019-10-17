<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'ITX.Jobs',
            'Frontend',
            [
                'Posting' => 'list, show, new, create, edit, update, delete, ',
                'Contact' => 'list, show, new, create, edit, update, delete',
                'Location' => 'list, show, new, create, edit, update, delete',
                'Application' => 'list, show, new, create, edit, update, delete'
            ],
            // non-cacheable actions
            [
                'Posting' => 'create, update, delete, ',
                'Contact' => 'create, update, delete',
                'Location' => 'create, update, delete',
                'Application' => 'create, update, delete'
            ]
        );

        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            'mod {
                wizards.newContentElement.wizardItems.plugins {
                    elements {
                        frontend {
                            iconIdentifier = jobs-plugin-frontend
                            title = LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_frontend.name
                            description = LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_frontend.description
                            tt_content_defValues {
                                CType = list
                                list_type = jobs_frontend
                            }
                        }
                    }
                    show = *
                }
           }'
        );
		$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
		
			$iconRegistry->registerIcon(
				'jobs-plugin-frontend',
				\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
				['source' => 'EXT:jobs/Resources/Public/Icons/user_plugin_frontend.svg']
			);
		
    }
);
