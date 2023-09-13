<?php

declare(strict_types=1);

namespace ITX\Jobapplications;

use ITX\Jobapplications\Widgets\Provider\ApplicationsPerPostingBarChartProvider;
use ITX\Jobapplications\Widgets\Provider\BackendModuleButtonProvider;
use ITX\Jobapplications\Widgets\Provider\PostingsActiveProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Dashboard\Widgets\BarChartWidget;
use TYPO3\CMS\Dashboard\Widgets\NumberWithIconWidget;

return function(ContainerConfigurator $configurator) {
	$services = $configurator->services();

	if (ExtensionManagementUtility::isLoaded('dashboard')) {
		$services->set(PostingsActiveProvider::class);

		$services->set(BackendModuleButtonProvider::class)->arg('$target', '');

		$services->set(ApplicationsPerPostingBarChartProvider::class);

		$services->set('dashboard.widget.postingsActive')
				 ->class(NumberWithIconWidget::class)
				 ->arg('$view', new Reference('dashboard.views.widget'))
				 ->arg('$dataProvider', new Reference(PostingsActiveProvider::class))
				 ->arg('$options', [
						'title' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:be.widget.postings_active.title',
						'subtitle' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:be.widget.postings_active.subtitle',
						'icon' => 'content-carousel-item-calltoaction',
						'description' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:be.widget.postings_active.description',
				])
				 ->tag('dashboard.widget',
					   [
						   'identifier' => 'postings-active',
						   'groupNames' => 'widgetGroup-jobapplications',
						   'title' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:be.widget.postings_active.title',
						   'description' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:be.widget.postings_active.description',
						   'iconIdentifier' => 'content-widget-number',
						   'height' => 'small',
						   'width' => 'small',
					   ]);

		$services->set('dashboard.widget.applicationsPerPostingBarChart')
				 ->class(BarChartWidget::class)
				 ->arg('$dataProvider', new Reference(ApplicationsPerPostingBarChartProvider::class))
				 ->arg('$view', new Reference('dashboard.views.widget'))
				 ->arg('$buttonProvider', new Reference(BackendModuleButtonProvider::class))
				 ->tag('dashboard.widget', [
						'identifier' => 'applicationsPerPostingBarChart',
						'groupNames' => 'widgetGroup-jobapplications',
						'title' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:be.widget.applications_per_posting.title',
						'description' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:be.widget.applications_per_posting.description',
						'iconIdentifier' => 'content-widget-chart-bar',
						'height' => 'medium',
						'width' => 'medium',
				 ]);

	}
};