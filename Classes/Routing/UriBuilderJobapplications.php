<?php

	namespace ITX\Jobapplications\Routing;

	use Psr\Http\Message\ServerRequestInterface;
	use TYPO3\CMS\Core\Http\ApplicationType;
	use TYPO3\CMS\Core\Utility\ArrayUtility;

	/**
	 * Class uriBuilderJobapplications
	 *
	 * @extends TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
	 * @package ITX\Jobapplications\Routing;
	 */
	class UriBuilderJobapplications extends \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
	{
		public function uriForFrontend(
			?string $actionName = null,
			?array  $controllerArguments = null,
			?string $controllerName = null,
			?string $extensionName = null,
			?string $pluginName = null,
			bool $absolute = false
		): string
		{
			$controllerArguments = $controllerArguments ?? [];

			if ($actionName !== null)
			{
				$controllerArguments['action'] = $actionName;
			}
			if ($controllerName !== null)
			{
				$controllerArguments['controller'] = $controllerName;
			}
			else
			{
				$controllerArguments['controller'] = $this->request->getControllerName();
			}
			if ($extensionName === null)
			{
				$extensionName = $this->request->getControllerExtensionName();
			}
			$isFrontend = ($GLOBALS['TYPO3_REQUEST'] ?? null) instanceof ServerRequestInterface
				&& ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend();
			if ($pluginName === null && $isFrontend)
			{
				$pluginName = $this->extensionService->getPluginNameByAction($extensionName, $controllerArguments['controller'], $controllerArguments['action'] ?? null);
			}
			if ($pluginName === null)
			{
				$pluginName = $this->request->getPluginName();
			}
			if ($isFrontend && $this->configurationManager->isFeatureEnabled('skipDefaultArguments'))
			{
				$controllerArguments = $this->removeDefaultControllerAndAction($controllerArguments, $extensionName, $pluginName);
			}
			if ($this->targetPageUid === null && $isFrontend)
			{
				$this->targetPageUid = $this->extensionService->getTargetPidByPlugin($extensionName, $pluginName);
			}
			if ($this->format !== '')
			{
				$controllerArguments['format'] = $this->format;
			}
			if ($this->argumentPrefix !== null)
			{
				$prefixedControllerArguments = [$this->argumentPrefix => $controllerArguments];
			}
			else
			{
				$pluginNamespace = $this->extensionService->getPluginNamespace($extensionName, $pluginName);
				$prefixedControllerArguments = [$pluginNamespace => $controllerArguments];
			}
			ArrayUtility::mergeRecursiveWithOverrule($this->arguments, $prefixedControllerArguments);

			$this->createAbsoluteUri = $absolute;

			return $this->buildFrontendUri();
		}
	}