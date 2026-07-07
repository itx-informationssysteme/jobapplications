<?php

declare(strict_types=1);

namespace ITX\Jobapplications\Updates;

use TYPO3\CMS\Core\Upgrades\AbstractListTypeToCTypeUpdate;
use TYPO3\CMS\Core\Attribute\UpgradeWizard;

#[UpgradeWizard('jobapplicationsPluginListTypeToCTypeUpdate')]
final class JobapplicationsPluginListTypeToCTypeUpdate extends AbstractListTypeToCTypeUpdate
{
    protected function getListTypeToCTypeMapping(): array
    {
        return [
            'jobapplications_frontend' => 'jobapplications_frontend',
            'jobapplications_detailview' => 'jobapplications_detailview',
            'jobapplications_applicationform' => 'jobapplications_applicationform',
            'jobapplications_contactdisplay' => 'jobapplications_contactdisplay',
            'jobapplications_successpage' => 'jobapplications_successpage',
        ];
    }

    public function getTitle(): string
    {
        return 'Migrate Jobapplications plugins from list_type to CType';
    }

    public function getDescription(): string
    {
        return 'Content elements using the deprecated "Plugin" (list) type with the Jobapplications '
            . 'list_type values are migrated to use the new dedicated CType values directly.';
    }
}
