<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,career_level,division,employment_type,terms_of_employment,company_description,job_description,role_description,skill_requirements,benefits,base_salary,required_documents,company_information',
        'iconfile' => 'EXT:jobs/Resources/Public/Icons/tx_jobs_domain_model_posting.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, date_posted, career_level, division, employment_type, terms_of_employment, company_description, job_description, role_description, skill_requirements, benefits, base_salary, valid_through, required_documents, company_information, applications, location, contact',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, date_posted, career_level, division, employment_type, terms_of_employment, company_description, job_description, role_description, skill_requirements, benefits, base_salary, valid_through, required_documents, company_information, applications, location, contact, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_jobs_domain_model_posting',
                'foreign_table_where' => 'AND {#tx_jobs_domain_model_posting}.{#pid}=###CURRENT_PID### AND {#tx_jobs_domain_model_posting}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],

        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'date_posted' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.date_posted',
            'config' => [
                'dbType' => 'date',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 7,
                'eval' => 'date',
                'default' => null,
            ],
        ],
        'career_level' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.career_level',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'division' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.division',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'employment_type' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.employment_type',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'terms_of_employment' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.terms_of_employment',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'company_description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.company_description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'fieldControl' => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
            
        ],
        'job_description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.job_description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'fieldControl' => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
            
        ],
        'role_description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.role_description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'fieldControl' => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
            
        ],
        'skill_requirements' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.skill_requirements',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'fieldControl' => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
            
        ],
        'benefits' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.benefits',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'fieldControl' => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
            
        ],
        'base_salary' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.base_salary',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'valid_through' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.valid_through',
            'config' => [
                'dbType' => 'date',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 7,
                'eval' => 'date',
                'default' => null,
            ],
        ],
        'required_documents' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.required_documents',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'company_information' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.company_information',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'applications' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.applications',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_jobs_domain_model_application',
                'foreign_field' => 'posting',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 0,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],

        ],
        'location' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.location',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_jobs_domain_model_location',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'contact' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_posting.contact',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_jobs_domain_model_contact',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
    
    ],
];
