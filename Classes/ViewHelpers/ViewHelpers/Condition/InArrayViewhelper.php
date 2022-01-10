<?php

	namespace ITX\Jobapplications\ViewHelpers;

	use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

	/**
	 * This file is part of the ITX\jobapplications project under GPLv2 or later.
	 *
	 * For the full copyright and license information, please read the
	 * LICENSE.md file that was distributed with this source code.
	 *
	 * in_array function
	 */
	class InArrayViewHelper extends AbstractConditionViewHelper
	{

		public function initializeArguments()
		{
			parent::initializeArguments();
			$this->registerArgument('haystack', 'mixed', 'View helper haystack ', true);
			$this->registerArgument('needle', 'string', 'View helper needle', true);
		}

		// php in_array viewhelper
		public function render()
		{

			$needle = $this->arguments['needle'];
			$haystack = $this->arguments['haystack'];

			if (!is_array($haystack))
			{
				return $this->renderElseChild();
			}

			if (in_array($needle, $haystack))
			{
				return $this->renderThenChild();
			}
			else
			{
				return $this->renderElseChild();
			}
		}
	}