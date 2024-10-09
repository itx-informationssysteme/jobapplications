<?php
	return [
		'ctrl' => [
			'title' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_contact',
			'label' => 'last_name',
			'label_alt' => 'first_name',
            'label_alt_force' => true,
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
			'searchFields' => 'last_name,email,phone,division',
			'iconfile' => 'EXT:jobapplications/Resources/Public/Icons/Extension.svg'
		],
		'types' => [
			'1' => ['showitem' => 'sys_language_uid,first_name,last_name,email,phone,division,photo,be_user,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,hidden,l10n_parent,l10n_diffsource,starttime,endtime'],
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
					'foreign_table' => 'tx_jobapplications_domain_model_contact',
					'foreign_table_where' => 'AND {#tx_jobapplications_domain_model_contact}.{#pid}=###CURRENT_PID### AND {#tx_jobapplications_domain_model_contact}.{#sys_language_uid} IN (-1,0)',
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
			'first_name' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_contact.first_name',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim, required'
				],
			],
			'last_name' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_contact.last_name',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim, required'
				],
			],
			'email' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_contact.email',
				'config' => [
					'type' => 'email',
					'size' => 30,
				]
			],
			'phone' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_contact.phone',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				],
			],
			'division' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_contact.division',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				],
			],
			'photo' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_contact.photo',
                'config' => [
                    'type' => 'file',
                    'maxitems' => 1,
                    'allowed' => 'common-image-types',
                ],
			],
			"be_user" => [
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_contact.be_user',
				'config' => [
					'type' => 'select',
					'renderType' => 'selectSingle',
					'default' => 0,
					'items' => [['', 0]],
					'minitems' => 0,
					'foreign_table' => 'be_users',
					'fieldWizard' => [
						'selectIcons' => [
							'disabled' => false,
						],
					],
				],
			]
		],
	];
