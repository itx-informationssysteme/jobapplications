<?php

namespace ITX\Jobapplications\ViewHelpers\Page;

use ITX\Jobapplications\Domain\Model\Constraint;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

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
	protected static array $reflectionCache = [];

	public function initializeArguments(): void
	{
		parent::initializeArguments();
		$this->registerArgument('page', 'string', 'ViewHelper page', true);
		$this->registerArgument('constraint', 'mixed', 'ViewHelper constraint', true);
	}

	/**
	 * @return array which contains the page and constraints for the pagination template
	 */
	public function render(): array
	{
		$page = (string)$this->arguments["page"];
		$constraintArgument = $this->arguments["constraint"];

		$returnArguments = [
				"page" => $page,
		];

		if ($constraintArgument === null) {
			return $returnArguments;
		}

		$properties = self::getReflectedProperties($constraintArgument);

		foreach ($properties as $property) {
			$type = $property->getType()?->getName() ?? 'array';

			$identifier = "constraint][{$property->getName()}]";
			if ($type === 'array') {
				$identifier .= "[]";
			}

			$returnArguments[$identifier] = $property->getValue($constraintArgument);
		}

		return $returnArguments;
	}

	protected static function getReflectedProperties(object $constraint): array
	{
		$class = get_class($constraint);

		return self::$reflectionCache[$class] ?? (new \ReflectionClass(get_class($constraint)))->getProperties();
	}
}
