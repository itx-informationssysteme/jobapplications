<?php
	return [
		'ctrl' => [
			'title' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_status',
			'label' => 'name',
			'tstamp' => 'tstamp',
			'crdate' => 'crdate',
			'versioningWS' => true,
			'languageField' => 'sys_language_uid',
			'transOrigPointerField' => 'l10n_parent',
			'transOrigDiffSourceField' => 'l10n_diffsource',
			'delete' => 'deleted',
			'adminOnly' => 1,
			'enablecolumns' => [
				'disabled' => 'hidden',
				'starttime' => 'starttime',
				'endtime' => 'endtime',
			],
			'searchFields' => 'name, followers',
			'iconfile' => 'EXT:jobapplications/Resources/Public/Icons/Extension.svg'
		],
		'types' => [
			'1' => ['showitem' => 'name,is_end_status,is_new_status,followers,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,hidden,sys_language_uid,starttime,endtime,l10n_parent,l10n_diffsource'],
		],
		'columns' => [
			'crdate' => [
				'exclude' => true,
				'label' => 'Creation date',
				'config' => [
					'type' => 'none',
					'format' => 'date',
					'eval' => 'date'
				]

			],
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
					'foreign_table' => 'tx_jobapplications_domain_model_application',
					'foreign_table_where' => 'AND {#tx_jobapplications_domain_model_application}.{#pid}=###CURRENT_PID### AND {#tx_jobapplications_domain_model_application}.{#sys_language_uid} IN (-1,0)',
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
					'type' => 'datetime',
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
					'type' => 'datetime',
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
			'name' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_status.name',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				],
			],
			'is_end_status' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_status.is_end_status',
				'config' => [
					'type' => 'check',
					'items' => [
						[
							'label' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled', 
							'value' => '',
							'group' => 'default',	
						]
					],
					'default' => 0,
				]
			],
			'is_new_status' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_status.is_new_status',
				'config' => [
					'type' => 'check',
					'items' => [
						[
							'label' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled', 
							'value' => '',
							'group'=> 'default',
						]
					],
					'default' => 0,
				]
			],
			'followers' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_status.followers',
				'config' => [
					'type' => 'select',
					'renderType' => 'selectMultipleSideBySide',
					'foreign_table' => 'tx_jobapplications_domain_model_status',
					'MM' => 'tx_jobapplications_domain_model_status_mm',
					'size' => 10,
					'autoSizeMax' => 30,
					'maxitems' => 9999,
					'multiple' => 0,
					'field_control' => [
						'editPopup' => [
							'module' => [
								'name' => 'wizard_edit',
							],
							'type' => 'popup',
							'title' => 'Edit',
							'popup_onlyOpenIfSelected' => 1,
							'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
						],
						'addRecord' => [
							'module' => [
								'name' => 'wizard_add',
							],
							'type' => 'script',
							'title' => 'Create new',
							'params' => [
								'table' => 'tx_jobapplications_domain_model_status',
								'pid' => '###CURRENT_PID###',
								'setValue' => 'prepend'
							],
						],
					],
				]
			]
		],
	];
