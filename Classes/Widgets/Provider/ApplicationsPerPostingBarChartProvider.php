<?php
	/***************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2019
	 *  All rights reserved
	 *
	 *  This script is part of the TYPO3 project. The TYPO3 project is
	 *  free software; you can redistribute it and/or modify
	 *  it under the terms of the GNU General Public License as published by
	 *  the Free Software Foundation; either version 3 of the License, or
	 *  (at your option) any later version.
	 *
	 *  The GNU General Public License can be found at
	 *  http://www.gnu.org/copyleft/gpl.html.
	 *
	 *  This script is distributed in the hope that it will be useful,
	 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
	 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 *  GNU General Public License for more details.
	 *
	 *  This copyright notice MUST APPEAR in all copies of the script!
	 ***************************************************************/

	namespace ITX\Jobapplications\Widgets\Provider;

	use TYPO3\CMS\Dashboard\Widgets\ChartDataProviderInterface;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use ITX\Jobapplications\Domain\Repository\PostingRepository;
	use ITX\Jobapplications\Domain\Model\Posting;
	use ITX\Jobapplications\Domain\Repository\ApplicationRepository;
	use TYPO3\CMS\Dashboard\Widgets\AbstractBarChartWidget;
	use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

	/**
	 * Class ApplicationsPerPostingBarChart
	 *
	 * @package ITX\Jobapplications\Widgets
	 */
	class ApplicationsPerPostingBarChartProvider implements ChartDataProviderInterface
	{
		/** @var array */
		protected array $labels = [];

		protected ApplicationRepository $applicationRepository;
		protected PostingRepository $postingRepository;

		public function __construct(PostingRepository $postingRepository, ApplicationRepository $applicationRepository) {
			$this->postingRepository = $postingRepository;
			$this->applicationRepository = $applicationRepository;
		}

		public function getChartData(): array
		{
			$postings = $this->postingRepository->findAllIncludingHiddenAndDeleted();

			$data = [];

			/** @var Posting $posting */
			foreach ($postings as $posting)
			{
				$applicationCount = $this->applicationRepository->findByPostingIncludingHiddenAndDeleted($posting->getUid())->count();
				$this->labels[] = $posting->getTitle();
				$data[] = $applicationCount;
			}

			return [
				'labels' => $this->labels,
				'datasets' => [
					[
						'label' => LocalizationUtility::translate('LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:be.widget.applications_per_posting.label', "jobapplications"),
						'backgroundColor' => '#E62E29',
						'border' => 0,
						'data' => $data
					]
				]
			];
		}
	}