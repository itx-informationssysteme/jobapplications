<?php

declare(strict_types=1);

return [
    \ITX\Jobapplications\Domain\Model\TtContent::class => [
        'tableName' => 'tt_content',
        'properties' => [
            'CType' => [
                'fieldName' => 'CType',
            ],
        ],
    ],
    \ITX\Jobapplications\Domain\Model\BackendUser::class => [
        'tableName' => 'be_users',
    ],
];
