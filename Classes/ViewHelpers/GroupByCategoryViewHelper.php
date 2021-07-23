<?php

	namespace ITX\Jobapplications\ViewHelpers;

	/*
	 * This file belongs to the package "TYPO3 Fluid".
	 * See LICENSE.txt that was shipped with this package.
	 */

	use ITX\Jobapplications\Domain\Model\Posting;
	use TYPO3\CMS\Extbase\Domain\Model\Category;
	use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
	use TYPO3Fluid\Fluid\Core\ViewHelper;
	use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
	use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

	/**
	 * Loop view helper which can be used as a for viewhelper for grouping postings into categories.
	 * Implements what a basic foreach()-PHP-method does.
	 *
	 * @api
	 */
	class GroupByCategoryViewHelper extends AbstractViewHelper
	{

		use CompileWithRenderStatic;

		/**
		 * @var boolean
		 */
		protected $escapeOutput = false;

		/**
		 * @param array                     $arguments
		 * @param \Closure                  $renderChildrenClosure
		 * @param RenderingContextInterface $renderingContext
		 *
		 * @return string
		 * @throws ViewHelper\Exception
		 */
		public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
		{
			$templateVariableContainer = $renderingContext->getVariableProvider();
			if (!isset($arguments['postings']))
			{
				return '';
			}
			if (is_object($arguments['postings']) && !$arguments['postings'] instanceof \Traversable)
			{
				throw new ViewHelper\Exception('GroupByCategoryViewHelper only supports arrays and objects implementing \Traversable interface', 1248728393);
			}

			if (isset($arguments['iteration']))
			{
				$iterationData = [
					'index' => 0,
					'cycle' => 1,
					'total' => count($arguments['postings'])
				];
			}

			/** Postings grouped by category */
			$groupCategoriesList = [];
			$groupPostingsList = [];
			$uncategorizedPostings = [];
			foreach ($arguments['postings'] as $posting)
			{
				/** @var $posting Posting */
				$categories = $posting->getCategories()->toArray();
				$hasCategories = false;
				foreach ($categories as $category)
				{
					$hasCategories = true;

					if (empty($arguments['categoryRestriction']) || in_array((string)$category->getUid(), $arguments['categoryRestriction'], true))
					{
						/** @var $category Category */
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
				$templateVariableContainer->add($arguments['groupAs'], $postingArray);
				$templateVariableContainer->add($arguments['categoryAs'], $groupCategoriesList[$categoryUid]);
				if (isset($arguments['key']))
				{
					$templateVariableContainer->add($arguments['key'], $categoryUid);
				}
				if (isset($arguments['iteration']))
				{
					$iterationData['isFirst'] = $iterationData['cycle'] === 1;
					$iterationData['isLast'] = $iterationData['cycle'] === $iterationData['total'];
					$iterationData['isEven'] = $iterationData['cycle'] % 2 === 0;
					$iterationData['isOdd'] = !$iterationData['isEven'];
					$templateVariableContainer->add($arguments['iteration'], $iterationData);
					$iterationData['index']++;
					$iterationData['cycle']++;
				}
				$output .= $renderChildrenClosure();
				$templateVariableContainer->remove($arguments['groupAs']);
				$templateVariableContainer->remove($arguments['categoryAs']);
				if (isset($arguments['key']))
				{
					$templateVariableContainer->remove($arguments['key']);
				}
				if (isset($arguments['iteration']))
				{
					$templateVariableContainer->remove($arguments['iteration']);
				}
			}

			$templateVariableContainer->add($arguments['uncategorized'], $uncategorizedPostings);

			return $output;
		}

		/**
		 * @return void
		 */
		public function initializeArguments()
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
