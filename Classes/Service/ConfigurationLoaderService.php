<?php

namespace ITX\Jobapplications\Service;

use GuzzleHttp\Psr7\ServerRequest;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ClassesConfigurationFactory;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;

class ConfigurationLoaderService
{
    protected ClassesConfigurationFactory $classesConfigurationFactory;
    protected ConfigurationManager $configurationManager;

    public function __construct(
        ClassesConfigurationFactory $classesConfigurationFactory,
        ConfigurationManager $configurationManager
    )
    {
        $this->classesConfigurationFactory = $classesConfigurationFactory;
        $this->configurationManager = $configurationManager;
    }

    public function initCliEnvironment(): void
    {
        if (PHP_SAPI === 'cli') {
            $this->classesConfigurationFactory->createClassesConfiguration();

            Bootstrap::initializeBackendAuthentication();

            /** @var SiteFinder $siteFinder */
            $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
            $site = array_values($siteFinder->getAllSites())[0];
            $language = $site->getLanguageById(0);
            $GLOBALS['BE_USER']->user['lang'] = $language->getLocale()->getLanguageCode();

            $serverRequest = new ServerRequest('GET', 'ThisURLDoesntDoAnything.lol');
            $serverRequest = $serverRequest->withAttribute('extbase', []);
            $serverRequest = $serverRequest->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_BE);
            $serverRequest = $serverRequest->withAttribute('language', $language);
            $this->configurationManager->setRequest($serverRequest);
        }
    }
}