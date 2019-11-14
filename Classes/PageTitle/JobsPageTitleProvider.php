<?php

	namespace ITX\Jobs\PageTitle;

	use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;

	/**
	 * Class JobsPageTitleProvider
	 *
	 * @package Vendor\Extension\PageTitle
	 */
	class JobsPageTitleProvider extends AbstractPageTitleProvider
	{
		/**
		 * @param string $title
		 */
		public function setTitle(string $title)
		{
			$this->title = $title;
		}
	}