<?php
	return [
		'ctrl' => [
			'title' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.location_requirements',
			'label' => 'name',
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
			'searchFields' => 'name,address,latitude,londitude',
			'iconfile' => 'EXT:jobapplications/Resources/Public/Icons/Extension.svg'
		],
		'types' => [
			'1' => ['showitem' => 'name,type,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,sys_language_uid,hidden,starttime,endtime,l10n_parent,l10n_diffsource'],
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
					'items' => [
						['', 0],
					],
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

			'name' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_location_requirement.name',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim',
					'required' => true
				]
			],
			'type' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_location_requirement.location_type',
				'config' => [
					'type' => 'select',
					'renderType' => 'selectSingle',
					'items' => [
						['LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_location_requirement.country', 'Country'],
						['LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_location_requirement.state', 'State'],
						['LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_location_requirement.city', 'City']
					]

				],
			]
		]
	];
