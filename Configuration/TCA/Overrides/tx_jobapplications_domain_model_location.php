<?php
	defined('TYPO3') || die();

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
		'jobapplications',
		'tx_jobapplications_domain_model_location'
	);
