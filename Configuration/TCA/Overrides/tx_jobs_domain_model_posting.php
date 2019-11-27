<?php
	defined('TYPO3_MODE') || die();

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jobs_domain_model_posting', 'EXT:jobs/Resources/Private/Language/locallang_csh_tx_jobs_domain_model_posting.xlf');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jobs_domain_model_posting');

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
		'jobs',
		'tx_jobs_domain_model_posting'
	);
