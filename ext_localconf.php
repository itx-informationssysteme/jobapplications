<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'ITX.Jobs',
            'Frontend',
            [
                'Posting' => 'list'
            ],
            // non-cacheable actions
            [
                'Posting' => 'list',
                'Application' => 'create'
            ]
        );

		\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
			'ITX.Jobs',
			'DetailView',
			[
				'Posting' => 'show',
			]
		);

		\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
			'ITX.Jobs',
			'ApplicationForm',
			[
				'Application' => 'new, create'
			],
			[
				'Application' => 'create'
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
				['source' => 'EXT:jobs/Resources/Public/Icons/logo_jobs.svg']
			);

			$iconRegistry->registerIcon(
				'jobs-plugin-detailview',
				\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
				['source' => 'EXT:jobs/Resources/Public/Icons/logo_jobs.svg']
			);

			$iconRegistry->registerIcon(
				'jobs-plugin-applicationform',
				\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
				['source' => 'EXT:jobs/Resources/Public/Icons/logo_jobs.svg']
			);
    }
);
