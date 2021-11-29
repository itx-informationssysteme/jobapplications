<?php
	defined('TYPO3_MODE') || die();

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
		'jobapplications',
		'tx_jobapplications_domain_model_location'
	);
