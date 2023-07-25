<?php

return [
    'jobapplications_backend' => [
        'parent' => 'web',
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/jobapplications',
        'extensionName' => 'Jobapplications',
        'controllerActions' => [
            \ITX\Jobapplications\Controller\BackendController::class => [
                'listApplications',
                'dashboard',
                'showApplication',
                'settings'
            ]
        ],
        'icon' => 'EXT:jobapplications/Resources/Public/Icons/logo_jobs.svg',
        'labels' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_backend.xlf',
        'navigationComponentId' => '',
        'inheritNavigationComponentFromMainModule' => false,
    ],
];
