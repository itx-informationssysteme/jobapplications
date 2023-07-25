<?php

namespace ITX\Jobapplications\Utility;

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Typo3VersionUtility
 *
 * @package ITX\Jobapplications\Utility
 */
class Typo3VersionUtility
{
    public static function getMajorVersion(): int
    {
        /** @var Typo3Version $version */
        $version = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);

        return $version->getMajorVersion();
    }
}
