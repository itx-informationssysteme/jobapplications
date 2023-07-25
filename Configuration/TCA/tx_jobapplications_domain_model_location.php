<?php
	return [
		'ctrl' => [
			'title' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_location',
			'label' => 'name',
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
			'searchFields' => 'name,address,latitude,londitude',
			'iconfile' => 'EXT:jobapplications/Resources/Public/Icons/Extension.svg'
		],
		'types' => [
			'1' => ['showitem' => 'name,address_street_and_number,address_addition,address_post_code,address_city,address_country,address_region,latitude,londitude,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,sys_language_uid,hidden,starttime,endtime,l10n_parent,l10n_diffsource'],
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
					'foreign_table' => 'tx_jobapplications_domain_model_location',
					'foreign_table_where' => 'AND {#tx_jobapplications_domain_model_location}.{#pid}=###CURRENT_PID### AND {#tx_jobapplications_domain_model_location}.{#sys_language_uid} IN (-1,0)',
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
            'categories' => [
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.category',
                'config' => [
                    'type' => 'category',
                    'relationship' => 'manyToMany'
                ],
            ],

			'name' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_location.name',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				],
			],
			'address_street_and_number' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_application.address_street_and_number',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				]
			],
			'address_addition' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_application.address_addition',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				],
			],
			'address_post_code' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_application.address_post_code',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				]
			],
			'address_city' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_application.address_city',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				],
			],
			'address_country' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_application.address_country',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				],
			],
			'address_region' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.address_region',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				],
			],
			'latitude' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_location.latitude',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				],
			],
			'londitude' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_location.londitude',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				],
			],

		],
	];
