<?php

	namespace ITX\Jobapplications\ViewHelpers;

	/*
	 * This file belongs to the package "TYPO3 Fluid".
	 * See LICENSE.txt that was shipped with this package.
	 */

	use Closure;
	use ITX\Jobapplications\Domain\Model\Posting;
	use TYPO3\CMS\Extbase\Domain\Model\Category;
	use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
	use TYPO3Fluid\Fluid\Core\ViewHelper;
	use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
	use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
	use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

	/**
	 * Loop view helper which can be used as a for viewhelper for grouping postings into categories.
	 * Implements what a basic foreach()-PHP-method does.
	 *
	 * @api
	 */
	class GroupByCategoryViewHelper extends AbstractViewHelper
	{
		/**
		 * @var boolean
		 */
		protected $escapeOutput = false;

		/**
		 * @return string
		 * @throws ViewHelper\Exception
		 */
		public function render(): string
		{
			$templateVariableContainer = $this->renderingContext->getVariableProvider();
			if (!isset($this->arguments['postings']))
			{
				return '';
			}
			if (is_object($this->arguments['postings']) && !$this->arguments['postings'] instanceof \Traversable)
			{
				throw new Exception('GroupByCategoryViewHelper only supports arrays and objects implementing \Traversable interface', 1248728393);
			}

			$iterationData = [];
			if (isset($this->arguments['iteration']))
			{
				$iterationData = [
					'index' => 0,
					'cycle' => 1,
					'total' => count($this->arguments['postings'])
				];
			}

			/** Postings grouped by category */
			$groupCategoriesList = [];
			$groupPostingsList = [];
			$uncategorizedPostings = [];
			foreach ($this->arguments['postings'] as $posting)
			{
				/** @var Posting $posting */
				$categories = $posting->getCategories()->toArray();
				$hasCategories = false;
				foreach ($categories as $category)
				{
					$hasCategories = true;

					if (empty($this->arguments['categoryRestriction']) || in_array((string)$category->getUid(), $this->arguments['categoryRestriction'], true))
					{
						/** @var Category $category */
						if (!array_key_exists($category->getUid(), $groupCategoriesList))
						{
							$groupCategoriesList[$category->getUid()] = $category;
						}

						$groupPostingsList[$category->getUid()][] = $posting;
					}
				}

				if ($hasCategories === false)
				{
					$uncategorizedPostings[] = $posting;
				}
			}

			$output = '';
			foreach ($groupPostingsList as $categoryUid => $postingArray)
			{
				$templateVariableContainer->add($this->arguments['groupAs'], $postingArray);
				$templateVariableContainer->add($this->arguments['categoryAs'], $groupCategoriesList[$categoryUid]);
				if (isset($this->arguments['key']))
				{
					$templateVariableContainer->add($this->arguments['key'], $categoryUid);
				}
				if (isset($this->arguments['iteration']))
				{
					$iterationData['isFirst'] = $iterationData['cycle'] === 1;
					$iterationData['isLast'] = $iterationData['cycle'] === $iterationData['total'];
					$iterationData['isEven'] = $iterationData['cycle'] % 2 === 0;
					$iterationData['isOdd'] = !$iterationData['isEven'];
					$templateVariableContainer->add($this->arguments['iteration'], $iterationData);
					$iterationData['index']++;
					$iterationData['cycle']++;
				}
				$output .= $this->render();
				$templateVariableContainer->remove($this->arguments['groupAs']);
				$templateVariableContainer->remove($this->arguments['categoryAs']);
				if (isset($this->arguments['key']))
				{
					$templateVariableContainer->remove($this->arguments['key']);
				}
				if (isset($this->arguments['iteration']))
				{
					$templateVariableContainer->remove($this->arguments['iteration']);
				}
			}

			$templateVariableContainer->add($this->arguments['uncategorized'], $uncategorizedPostings);

			return $output;
		}

		/**
		 * @return void
		 */
		public function initializeArguments(): void
		{
			parent::initializeArguments();
			$this->registerArgument('postings', 'array', 'The postings to group', true);
			$this->registerArgument('groupAs', 'string', 'The name of the group iteration variable', true);
			$this->registerArgument('categoryAs', 'string', 'Variable to current category to', true);
			$this->registerArgument('categoryRestriction', 'array', 'Restrict categories to specific categories');
			$this->registerArgument('key', 'string', 'Variable to assign current key to', false);
			$this->registerArgument('iteration', 'string', 'The name of the variable to store iteration information (index, cycle, isFirst, isLast, isEven, isOdd)');
			$this->registerArgument('uncategorized', 'string', 'The name of the variable to store uncategorized postings in');
		}
	}
