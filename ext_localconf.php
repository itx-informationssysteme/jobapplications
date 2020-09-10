<?php
	defined('TYPO3_MODE') || die('Access denied.');

	call_user_func(
		function () {

			\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
				'ITX.Jobapplications',
				'Frontend',
				[
					'Posting' => 'list'
				],
				// non-cacheable actions
				[
					'Posting' => 'list'
				]
			);

			\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
				'ITX.Jobapplications',
				'DetailView',
				[
					'Posting' => 'show',
				]
			);

			\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
				'ITX.Jobapplications',
				'ApplicationForm',
				[
					'Application' => 'new, create'
				],
				[
					'Application' => 'create, new'
				]
			);

			\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
				'ITX.Jobapplications',
				'ContactDisplay',
				[
					'Contact' => 'list'
				]
			);

			\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
				'ITX.Jobapplications',
				'SuccessPage',
				[
					'Application' => 'success'
				],
				[
					'Application' => 'success'
				]
			);

			// wizards
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
				'mod {
                wizards.newContentElement.wizardItems.plugins {
                    elements {
                        frontend {
                            iconIdentifier = jobapplications-plugin-frontend
                            title = LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_frontend.name
                            description = LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:tx_jobapplications_frontend.description
                            tt_content_defValues {
                                CType = list
                                list_type = jobapplications_frontend
                            }
                        }
                    }
                    	show = *
                	}
           		}'
			);
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(trim('
    			config.pageTitleProviders {
        			own {
            			provider = ITX\Jobapplications\PageTitle\JobsPageTitleProvider
            			before = record
            			after = altPageTitle
					}			
				}
			'));
			$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

			$iconRegistry->registerIcon(
				'jobapplications-plugin-frontend',
				\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
				['source' => 'EXT:jobapplications/Resources/Public/Icons/logo_jobs.svg']
			);

			$iconRegistry->registerIcon(
				'jobapplications-plugin-detailview',
				\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
				['source' => 'EXT:jobapplications/Resources/Public/Icons/logo_jobs.svg']
			);

			$iconRegistry->registerIcon(
				'jobapplications-plugin-applicationform',
				\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
				['source' => 'EXT:jobapplications/Resources/Public/Icons/logo_jobs.svg']
			);

			// Add CleanUpApplications task
			$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\ITX\Jobapplications\Task\CleanUpApplications::class] = array(
				'extension' => 'jobapplications',
				'title' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:CleanUpApplications.title',
				'description' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:CleanUpApplications.description',
				'additionalFields' => \ITX\Jobapplications\Task\CleanUpApplicationsAdditionalFieldProvider::class
			);

			// Add Anonymize Applications task
			$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\ITX\Jobapplications\Task\AnonymizeApplications::class] = array(
				'extension' => 'jobapplications',
				'title' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:AnonymizeApplications.title',
				'description' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:AnonymizeApplications.description',
				'additionalFields' => \ITX\Jobapplications\Task\CleanUpApplicationsAdditionalFieldProvider::class
			);

			$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['jobapplications'] = \ITX\Jobapplications\Hooks\TCEmainHook::class;
			$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['jobapplications'] = \ITX\Jobapplications\Hooks\TCEmainHook::class;

			// FluidMail
			$GLOBALS['TYPO3_CONF_VARS']['MAIL']['templateRootPaths'][283] = 'EXT:jobapplications/Resources/Private/Templates/Mail';

			// Ajax routes
			$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['jobapplications_upload'] = \ITX\Jobapplications\Controller\AjaxController::class . '::uploadAction';
			$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['jobapplications_revert'] = \ITX\Jobapplications\Controller\AjaxController::class . '::revertAction';

			// Cache
			if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['jobapplications_cache'])) {
				$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['jobapplications_cache'] = [];
			}

			$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludeAllEmptyParameters'] = true;
			$GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFoundOnCHashError'] = false;
		}
	);
