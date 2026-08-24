<?php

namespace ITX\Jobapplications\Routing;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Routing\RouterInterface;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;

class UriBuilderJobapplications extends \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
{
    public function uriForFrontend(
        ?string $actionName = null,
        ?array $controllerArguments = null,
        ?string $controllerName = null,
        ?string $extensionName = null,
        ?string $pluginName = null,
        bool $absolute = false
    ): string {
        $isFrontend = ($GLOBALS['TYPO3_REQUEST'] ?? null) instanceof ServerRequestInterface
            && ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend();

        if ($isFrontend) {
            return $this->uriForFrontendFromRequest($actionName, $controllerArguments, $controllerName, $extensionName, $pluginName, $absolute);
        }

        return $this->uriForFrontendViaSiteRouter($actionName, $controllerArguments, $controllerName, $extensionName, $pluginName, $absolute);
    }

    private function uriForFrontendFromRequest(
        ?string $actionName,
        ?array $controllerArguments,
        ?string $controllerName,
        ?string $extensionName,
        ?string $pluginName,
        bool $absolute
    ): string {
        $controllerArguments = $controllerArguments ?? [];
        if ($actionName !== null) {
            $controllerArguments['action'] = $actionName;
        }
        if ($controllerName !== null) {
            $controllerArguments['controller'] = $controllerName;
        } else {
            $controllerArguments['controller'] = $this->request->getControllerName();
        }
        if ($extensionName === null) {
            $extensionName = $this->request->getControllerExtensionName();
        }
        if ($pluginName === null) {
            $pluginName = $this->extensionService->getPluginNameByAction($extensionName, $controllerArguments['controller'], $controllerArguments['action'] ?? null);
        }
        if ($pluginName === null) {
            $pluginName = $this->request->getPluginName();
        }
        if ($this->targetPageUid === null) {
            $this->targetPageUid = $this->extensionService->getTargetPidByPlugin($extensionName, $pluginName);
        }
        if ($this->format !== '') {
            $controllerArguments['format'] = $this->format;
        }
        if ($this->argumentPrefix !== null) {
            $prefixedControllerArguments = [$this->argumentPrefix => $controllerArguments];
        } else {
            $pluginNamespace = $this->extensionService->getPluginNamespace($extensionName, $pluginName);
            $prefixedControllerArguments = [$pluginNamespace => $controllerArguments];
        }
        ArrayUtility::mergeRecursiveWithOverrule($this->arguments, $prefixedControllerArguments);
        $this->createAbsoluteUri = $absolute;
        return $this->buildFrontendUri();
    }

    /**
     * @throws \RuntimeException
     */
    private function uriForFrontendViaSiteRouter(
        ?string $actionName,
        ?array $controllerArguments,
        ?string $controllerName,
        ?string $extensionName,
        ?string $pluginName,
        bool $absolute
    ): string {
        if ($this->targetPageUid === null || $controllerName === null || $extensionName === null || $actionName === null) {
            throw new \RuntimeException(
                'UriBuilderJobapplications::uriForFrontend() called outside a real frontend request',
                1734000003
            );
        }

        $controllerArguments = $this->convertDomainObjectsToUids($controllerArguments ?? []);
        $controllerArguments['action'] = $actionName;
        $controllerArguments['controller'] = $controllerName;
        if ($this->format !== '') {
            $controllerArguments['format'] = $this->format;
        }

        $argumentKey = $this->argumentPrefix
            ?? (strtolower($extensionName) . '_' . strtolower($pluginName ?? ''));

        $queryParameters = $this->arguments;
        ArrayUtility::mergeRecursiveWithOverrule($queryParameters, [$argumentKey => $controllerArguments]);

        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);

        try {
            $site = $siteFinder->getSiteByPageId($this->targetPageUid);
        } catch (SiteNotFoundException $e) {
            throw new \RuntimeException(
                'UriBuilderJobapplications could not resolve a site configuration for page uid ' .
                $this->targetPageUid . '. Make sure that page is part of a properly configured site.',
                1734000002,
                $e
            );
        }

        $uri = $site->getRouter()->generateUri(
            $this->targetPageUid,
            $queryParameters,
            '',
            $absolute ? RouterInterface::ABSOLUTE_URL : RouterInterface::ABSOLUTE_PATH
        );

        return (string)$uri;
    }

    private function convertDomainObjectsToUids(array $arguments): array
    {
        foreach ($arguments as $key => $value) {
            if ($value instanceof AbstractDomainObject) {
                $arguments[$key] = $value->getUid();
            } elseif (is_array($value)) {
                $arguments[$key] = $this->convertDomainObjectsToUids($value);
            }
        }
        return $arguments;
    }
}
