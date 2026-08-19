<?php

declare(strict_types=1);

namespace ITX\Jobapplications\Widgets\Provider;

use TYPO3\CMS\Backend\Module\ModuleProvider;
use TYPO3\CMS\Dashboard\Widgets\ButtonProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\ElementAttributesInterface;

final class BackendModuleButtonProvider implements ButtonProviderInterface, ElementAttributesInterface
{
    private const MODULE_IDENTIFIER = 'jobapplications_backend';

    public function __construct(private readonly ModuleProvider $moduleProvider) {}

    public function getTitle(): string
    {
        return 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf:be.widget.applications_per_posting.button';
    }

    public function getLink(): string
    {
        return '';
    }

    public function getTarget(): string
    {
        return '';
    }

    public function getElementAttributes(): array
    {
        if (!$this->moduleProvider->accessGranted(self::MODULE_IDENTIFIER, $GLOBALS['BE_USER'])) {
            return [
                'hidden' => 'hidden',
            ];
        }

        return [
            'data-dispatch-action' => 'TYPO3.ModuleMenu.showModule',
            'data-dispatch-args-list' => self::MODULE_IDENTIFIER,
        ];
    }
}
