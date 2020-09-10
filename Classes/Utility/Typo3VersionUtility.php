<?php

	namespace ITX\Jobapplications\Utility;

	use TYPO3\CMS\Core\Utility\GeneralUtility;

	/**
	 * Class Typo3VersionUtility
	 *
	 * @package ITX\Jobapplications\Utility
	 */
	class Typo3VersionUtility
	{
		public static function getMajorVersion(): int {
			$version = null;
			if (constant('TYPO3_version'))
			{
				$version = (int)(constant('TYPO3_version'));
			}
			else
			{
				/** @var Typo3Version $version */
				$version = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);

				$version = $version->getMajorVersion();
			}
			return $version;
		}
	}