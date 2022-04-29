<?php

	namespace ITX\Jobapplications\ViewHelpers\Format;

	use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

	/**
	 * This file is part of the ITX\jobapplications project under GPLv2 or later.
	 *
	 * For the full copyright and license information, please read the
	 * LICENSE.md file that was distributed with this source code.
	 *
	 * replaces string with substring
	 */

	class ReplaceStringViewHelper extends AbstractViewHelper
	{
		public function initializeArguments() {
			parent::initializeArguments();
			$this->registerArgument('substring', 'string', 'View helper substring ', TRUE);
			$this->registerArgument('content', 'string', 'View helper content', TRUE);
			$this->registerArgument('replacement', 'string', 'View helper replacement', TRUE);
		}

		/**
		 * Render method
		 *
		 * @return string
		 */
		public function render()
		{
			return str_replace($this->arguments['substring'], $this->arguments['replacement'], $this->arguments['content']);
		}
	}