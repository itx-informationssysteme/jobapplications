<?php
$slugBehaviour = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)
                                                       ->get('jobapplications', 'slugBehaviour');

return [
    'ctrl' => [
        'title' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
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
        'iconfile' => 'EXT:jobapplications/Resources/Public/Icons/Extension.svg'
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language'
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'foreign_table' => 'tx_jobapplications_domain_model_posting',
                'foreign_table_where' => 'AND {#tx_jobapplications_domain_model_posting}.{#pid}=###CURRENT_PID### AND {#tx_jobapplications_domain_model_posting}.{#sys_language_uid} IN (-1,0)',
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
            ],
        ],
        'deleted' => [
            'exclude' => 1,
            'label' => 'Deleted',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.starttime',
            'config' => [
                'type' => 'datetime',
                'eval' => 'datetime,int',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.valid_through',
            'config' => [
                'type' => 'datetime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'categories' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.category',
            'config' => [
                'type' => 'category',
                'relationship' => 'manyToMany'
            ],
        ],
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true
            ],
        ],
        'date_posted' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.date_posted',
            'config' => [
                'dbType' => 'date',
                'type' => 'datetime',
                'size' => 7,
                'eval' => 'date',
                'required' => true
            ],
        ],
        'career_level' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.career_level',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'division' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.division',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'employment_type' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.employment_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'relationship' => 'oneToMany',
                'items' => [
                    [
                        'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang.xlf:fe.posting.employment.selector.fulltime',
                        'value' => "fulltime",
                        'group' => 'default'
                    ],
                    [
                        'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang.xlf:fe.posting.employment.selector.parttime',
                        'value' => "parttime",
                        'group' => 'default',
                    ],
                    [
                        'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang.xlf:fe.posting.employment.selector.contractor',
                        'value' => "contractor",
                        'group' => 'default',
                    ],
                    [
                        'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang.xlf:fe.posting.employment.selector.temporary',
                        'value' => "temporary",
                        'group' => 'default',
                    ],
                    [
                        'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang.xlf:fe.posting.employment.selector.intern',
                        'value' => "intern",
                        'group' => 'default',
                    ],
                    [
                        'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang.xlf:fe.posting.employment.selector.volunteer',
                        'value' => "volunteer",
                        'group' => 'default',
                    ],
                    [
                        'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang.xlf:fe.posting.employment.selector.perdiem',
                        'value' => "perdiem",
                        'group' => 'default',
                    ],
                    [
                        'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang.xlf:fe.posting.employment.selector.other',
                        'value' => "other",
                        'group' => 'default',
                    ]
                ],
                'eval' => ''
            ],
        ],
        'terms_of_employment' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.terms_of_employment',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'company_description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.company_description',
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
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.job_description',
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
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.role_description',
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
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.skill_requirements',
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
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.benefits',
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
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.base_salary',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'required_documents' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.required_documents',
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
        'company_information' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.company_information',
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
        'detail_view_image' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.detail_view_image',
            'config' => [
                'type' => 'file',
                'maxitems' => 1,
                'allowed' => 'common-image-types',
            ],
        ],
        'list_view_image' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.list_view_image',
            'config' => [
                'type' => 'file',
                'maxitems' => 1,
                'allowed' => 'common-image-types',
            ],
        ],
        'locations' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.locations',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_jobapplications_domain_model_location',
                'foreign_table_where' => 'tx_jobapplications_domain_model_location.sys_language_uid IN (0,-1) ORDER BY tx_jobapplications_domain_model_location.name ASC',
                'MM' => 'tx_jobapplications_postings_locations_mm',
                'size' => 3,
                'autoSizeMax' => 5,
            ],
        ],
        'homeoffice' => [
            'excluded' => true,
            'onChange' => 'reload',
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.homeoffice',
            'config' => [
                'type' => 'check',
            ]
        ],
        'locationrequirements' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_location_requirement.name',
            'displayCond' => 'FIELD:homeoffice:REQ:true',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'foreign_table' => 'tx_jobapplications_domain_model_locationrequirement',
                'foreign_table_where' => 'tx_jobapplications_domain_model_locationrequirement.sys_language_uid IN (0,-1) ORDER BY tx_jobapplications_domain_model_locationrequirement.name ASC',
                'minitems' => 0,
                'maxitems' => 1,
                'required' => true
            ]
        ],
        'contact' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.contact',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_jobapplications_domain_model_contact',
                'minitems' => 0,
                'maxitems' => 1,
                'allowNonIdValues' => true
            ],
        ],
        'slug' => [
            'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.slug',
            'exclude' => true,
            'config' => [
                'type' => 'slug',
                'generatorOptions' => [
                    'fields' => ['title'],
                    'fieldSeparator' => '/',
                    'replacements' => [
                        '/' => '',
                    ],
                ],
                'fallbackCharacter' => '-',
                'eval' => $slugBehaviour,
                'size' => 50,
                'default' => ''
            ],
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => '--palette--;;mainInfo,--palette--;;relations,--palette--;;dates,--palette--;;circumstances, --palette--;;homeoffice-settings,
                            --div--;LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.title.advanced,
                                --palette--;;general,
                            --div--;LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.title.texts,
                                company_description,job_description,role_description,skill_requirements,benefits,required_documents,company_information,
                            --div--;LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.title.images,
                                --palette--;;images,
                            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                                categories,'
        ],
    ],
    'palettes' => [
        'general' => [
            'showitem' => 'sys_language_uid, hidden, --linebreak--, slug, l10n_parent, l10n_diffsource',
        ],
        'mainInfo' => [
            'showitem' => 'title, division'
        ],
        'circumstances' => [
            'showitem' => 'base_salary, career_level, --linebreak--, employment_type, terms_of_employment'
        ],
        'dates' => [
            'showitem' => 'date_posted, starttime ,endtime'
        ],
        'relations' => [
            'showitem' => 'contact, --linebreak--, locations,'
        ],
        'images' => [
            'showitem' => 'detail_view_image, list_view_image'
        ],
        'homeoffice-settings' => [
            'showitem' => 'homeoffice, locationrequirements'
        ]
    ],
];
