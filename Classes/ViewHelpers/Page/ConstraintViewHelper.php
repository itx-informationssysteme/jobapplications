<?php

	namespace ITX\Jobapplications\ViewHelpers\Page;

	use ITX\Jobapplications\Domain\Model\Constraint;
	use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
	use function _PHPStan_67a5964bf\RingCentral\Psr7\str;

	/**
	 * This file is part of the ITX\jobapplications project under GPLv2 or later.
	 *
	 * For the full copyright and license information, please read the
	 * LICENSE.md file that was distributed with this source code.
	 *
	 * replaces string with substring
	 */

	class ConstraintViewHelper extends AbstractViewHelper
	{
		public function initializeArguments() {
			parent::initializeArguments();
			$this->registerArgument('page', 'string', 'ViewHelper page', true);
			$this->registerArgument('constraint', 'mixed', 'ViewHelper constraint', true);
		}

		/**
		 * @return array which contains the page and constraints for the pagination template
		 */
		public function render()
		{
			$page = $this->arguments["page"];
			/** @var Constraint $constraint */
			$constraintArgument = $this->arguments["constraint"];

			return  [
				"page" => $page,
				"constraint][division][]" => $constraintArgument->getDivision() ?? "",
				"constraint][careerLevel][]" => $constraintArgument->getCareerLevel() ?? "",
				"constraint][employmentType][]" => $constraintArgument->getEmploymentType() ?? "",
				"constraint][locations][]" => $constraintArgument->getLocations() ?? "",
				];
		}
	}
