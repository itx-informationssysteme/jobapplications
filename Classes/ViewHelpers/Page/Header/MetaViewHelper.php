<?php

	namespace ITX\Jobapplications\ViewHelpers\Page\Header;

	/*
	 * This file is part of the FluidTYPO3/Vhs project under GPLv2 or later.
	 *
	 * For the full copyright and license information, please read the
	 * LICENSE.md file that was distributed with this source code.
	 */

	use ITX\Jobapplications\Traits\PageRendererTrait;
	use ITX\Jobapplications\Traits\TagViewHelperTrait;
	use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

	/**
	 * ViewHelper used to render a meta tag
	 *
	 * If you use the ViewHelper in a plugin it has to be USER
	 * not USER_INT, what means it has to be cached!
	 */
	class MetaViewHelper extends AbstractTagBasedViewHelper
	{

		use TagViewHelperTrait;
		use PageRendererTrait;

		/**
		 * @var    string
		 */
		protected $tagName = 'meta';

		/**
		 * Arguments initialization
		 *
		 * @return void
		 */
        public function initializeArguments(): void
        {
            parent::initializeArguments();
            $this->registerArgument('name', 'string', 'Name property of meta tag');
            $this->registerArgument('http-equiv', 'string', 'Property: http-equiv');
            $this->registerArgument('property', 'string', 'Property of meta tag');
            $this->registerArgument('content', 'string', 'Content of meta tag');
            $this->registerArgument('scheme', 'string', 'Property: scheme');
            $this->registerArgument('lang', 'string', 'Property: lang');
            $this->registerArgument('dir', 'string', 'Property: dir');
        }

		/**
		 * Render method
		 *
		 * @return string
		 */
        public function render(): string
        {
            $content = $this->arguments['content'];

            if (!empty($content)) {
                $type = 'name';
                $name = $this->arguments['name'] ?? null;

                if (!empty($this->arguments['property'])) {
                    $type = 'property';
                    $name = $this->arguments['property'];
                } elseif (!empty($this->arguments['http-equiv'])) {
                    $type = 'http-equiv';
                    $name = $this->arguments['http-equiv'];
                }

                if (empty($name)) {
                    // Nothing usable to register — bail out safely rather than fatal.
                    return '';
                }

                $pageRenderer = static::getPageRenderer();
                $properties = [];

                foreach (['http-equiv', 'property', 'scheme', 'lang', 'dir'] as $propertyName) {
                    if (!empty($this->arguments[$propertyName])) {
                        $properties[$propertyName] = $this->arguments[$propertyName];
                    }
                }

                $pageRenderer->setMetaTag($type, $name, $content, $properties);
            }

            return '';
        }
	}
