<?php

return [
    'frontend' => [
        'itx/jobapplications/fileupload' => [
            'target' => \ITX\Jobapplications\Middleware\FileUploadMiddleware::class,
            'before' => [
                'typo3/cms-frontend/timetracker',
            ],
            'after' => [],
        ],
        'itx/jobapplications/file-revert' => [
            'target' => \ITX\Jobapplications\Middleware\FileRevertMiddleware::class,
            'before' => [
                'typo3/cms-frontend/timetracker',
            ],
            'after' => [
                'itx/jobapplications/fileupload',
            ],
        ],
    ],
];
