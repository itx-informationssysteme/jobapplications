<?php

use ITX\Jobapplications\Controller\ApplicationController;
use ITX\Jobapplications\Controller\PostingController;

defined('TYPO3') || die('Access denied.');

	call_user_func(
		function () {

			\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
				'Jobapplications',
				'Frontend',
				[
					PostingController::class => 'list'
				],
				// non-cacheable actions
				[
					PostingController::class => 'list'
				]
			);

			\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
				'Jobapplications',
				'DetailView',
				[
					PostingController::class => 'show',
				]
			);

			\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
				'Jobapplications',
				'ApplicationForm',
				[
					ApplicationController::class => 'new, create'
				],
				[
					ApplicationController::class => 'create, new'
				]
			);

			\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
				'Jobapplications',
				'ContactDisplay',
				[
					\ITX\Jobapplications\Controller\ContactController::class => 'list'
				]
			);

			\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
				'Jobapplications',
				'SuccessPage',
				[
					ApplicationController::class => 'success'
				],
				[
					ApplicationController::class => 'success'
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

			$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['jobapplications'] = \ITX\Jobapplications\Hooks\TCEmainHook::class;
			$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['jobapplications'] = \ITX\Jobapplications\Hooks\TCEmainHook::class;

			// FluidMail
			$GLOBALS['TYPO3_CONF_VARS']['MAIL']['templateRootPaths'][283] = 'EXT:jobapplications/Resources/Private/Templates/Mail';

			// Ajax routes
			$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['jobapplications_upload'] = \ITX\Jobapplications\Controller\AjaxController::class.'::uploadAction';
			$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['jobapplications_revert'] = \ITX\Jobapplications\Controller\AjaxController::class.'::revertAction';

			// Cache
			if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['jobapplications_cache']) || !is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['jobapplications_cache']))
			{
				$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['jobapplications_cache'] = [];
			}
			
			$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['jobapplications_makeLocationsMultiple']
				= \ITX\Jobapplications\Updates\MakeLocationsMultiple::class;
		}
	);
