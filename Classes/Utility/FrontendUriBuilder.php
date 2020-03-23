<?php

	namespace ITX\Jobapplications\Utility;

	use TYPO3\CMS\Core\Utility\GeneralUtility;

	/**
	 * Class FrontendUriBuilder
	 *
	 * @package ITX\Jobapplications\Hooks
	 */
	class FrontendUriBuilder
	{

		private $pageId = 1;

		private $extensionName = null;

		private $pluginName = null;

		private $actionName = null;

		private $controllerName = null;

		private $arguments = null;

		private $host = null;

		/**
		 * FrontendUriBuilder constructor.
		 */
		public function __construct()
		{
		}

		/**
		 * @param int $pageId the target pageId
		 *
		 * @return $this FrontendUriBuilder
		 */
		public function setPageId($pageId = 1)
		{
			$this->pageId = $pageId;

			return $this;
		}

		/**
		 * @param string $extensionName
		 *
		 * @return $this FrontendUriBuilder
		 */
		public function setExtensionName($extensionName)
		{
			$this->extensionName = $extensionName;

			return $this;
		}

		/**
		 * @param string $pluginName
		 *
		 * @return $this FrontendUriBuilder
		 */
		public function setPlugin($pluginName)
		{
			$this->pluginName = $pluginName;

			return $this;
		}

		/**
		 * @param string $actionName
		 *
		 * @return $this FrontendUriBuilder
		 */
		public function setAction($actionName)
		{
			$this->actionName = $actionName;

			return $this;
		}

		/**
		 * @param string $controllerName
		 *
		 * @return $this FrontendUriBuilder
		 */
		public function setController($controllerName)
		{
			$this->controllerName = $controllerName;

			return $this;
		}

		/**
		 * @param array $arguments like array('nameOfTheClass' => $instance)
		 *
		 * @return $this FrontendUriBuilder
		 */
		public function setArguments($arguments)
		{
			$this->arguments = $arguments;

			return $this;
		}

		/**
		 * @param string $host
		 *
		 * @return $this FrontendUriBuilder
		 */
		public function setHost($host)
		{
			$this->host = $host;

			return $this;
		}

		/**
		 * Build the URL
		 *
		 * @return string the url
		 * @throws \Exception
		 */
		public function build()
		{

			if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
				$http = 'https://';
			} else {
				$http = 'http://';
			}

			//set base, may need to find a better method in the future
			$url = $http.$_SERVER['HTTP_HOST'];

			//set pageId
			$url .= '/index.php?id='.$this->pageId;

			//set action
			if (!is_null($this->actionName))
			{
				$this->checkExtensionName();
				$this->checkPluginName();

				$url = $url.'&tx_'.$this->extensionName.'_'.$this->pluginName.'[action]='.$this->actionName;
			}

			//set controller
			if (!is_null($this->controllerName))
			{
				$this->checkExtensionName();
				$this->checkPluginName();

				$url = $url.'&tx_'.$this->extensionName.'_'.$this->pluginName.'[controller]='.ucfirst($this->controllerName);
			}

			//set arguments
			if (!is_null($this->arguments))
			{
				$this->checkExtensionName();
				$this->checkPluginName();

				/**
				 * @var $argument AbstractEntity
				 */
				foreach ($this->arguments as $key => $argument)
				{
					$url = $url.'&tx_'.$this->extensionName.'_'.$this->pluginName.'['.$key.']='.$argument->getUid();
				}
			}

			return $url;
		}

		private function checkExtensionName()
		{
			if (is_null($this->extensionName))
			{
				throw new \Exception("Extension name for FrontendUriBuilder not set!");
			}
		}

		private function checkPluginName()
		{
			if (is_null($this->pluginName))
			{
				throw new \Exception("Plugin name for FrontendUriBuilder not set!");
			}
		}
	}