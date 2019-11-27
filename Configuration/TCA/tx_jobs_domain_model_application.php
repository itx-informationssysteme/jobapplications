<?php
	return [
		'ctrl' => [
			'title' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application',
			'label' => 'last_name',
			'tstamp' => 'tstamp',
			'crdate' => 'crdate',
			'cruser_id' => 'cruser_id',
			'versioningWS' => true,
			'languageField' => 'sys_language_uid',
			'transOrigPointerField' => 'l10n_parent',
			'transOrigDiffSourceField' => 'l10n_diffsource',
			//'delete' => 'deleted', //commented out => delete instantaneously because privacy law things
			'adminOnly' => 1,
			'enablecolumns' => [
				'disabled' => 'hidden',
				'starttime' => 'starttime',
				'endtime' => 'endtime',
			],
			'searchFields' => 'first_name,last_name,email,phone,address_street_and_number,address_addition,address_city,address_country,salary_expectation',
			'iconfile' => 'EXT:jobs/Resources/Public/Icons/tx_jobs_domain_model_application.gif'
		],
		'interface' => [
			'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, salutation, first_name, last_name, email, phone, address_street_and_number, address_addition, address_post_code, address_city, address_country, salary_expectation, earliest_date_of_joining, cv, cover_letter, testimonials, other_files, privacy_agreement, posting, archived, status',
		],
		'types' => [
			'1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, salutation, first_name, last_name, email, phone, address_street_and_number, address_addition, address_post_code, address_city, address_country, salary_expectation, earliest_date_of_joining, cv, cover_letter, testimonials, other_files, privacy_agreement, posting, archived, status, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
		],
		'columns' => [
			'crdate' => [
				'exclude' => true,
				'label' => 'Creation date',
				'config' => Array(
					'type' => 'none',
					'format' => 'date',
					'eval' => 'date'
				)

			],
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
					'foreign_table' => 'tx_jobs_domain_model_application',
					'foreign_table_where' => 'AND {#tx_jobs_domain_model_application}.{#pid}=###CURRENT_PID### AND {#tx_jobs_domain_model_application}.{#sys_language_uid} IN (-1,0)',
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

			'salutation' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.salutation',
				'config' => [
					'type' => 'select',
					'renderType' => 'selectSingle',
					'items' => [
						['LLL:EXT:jobs/Resources/Private/Language/locallang.xlf:fe.application.selector.chooseMessage', ''],
						['LLL:EXT:jobs/Resources/Private/Language/locallang.xlf:fe.application.selector.mr', 'mr'],
						['LLL:EXT:jobs/Resources/Private/Language/locallang.xlf:fe.application.selector.mrs', 'mrs'],
						['LLL:EXT:jobs/Resources/Private/Language/locallang.xlf:fe.application.selector.div', 'div']
					],
					'size' => 1,
					'maxitems' => 1,
					'eval' => ''
				],
			],
			'first_name' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.first_name',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				],
			],
			'last_name' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.last_name',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				],
			],
			'email' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.email',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'nospace,email'
				]
			],
			'phone' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.phone',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				],
			],
			'address_street_and_number' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.address_street_and_number',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				]
			],
			'address_addition' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.address_addition',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				],
			],
			'address_post_code' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.address_post_code',
				'config' => [
					'type' => 'input',
					'size' => 4,
					'eval' => 'int'
				]
			],
			'address_city' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.address_city',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				],
			],
			'address_country' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.address_country',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				],
			],
			'salary_expectation' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.salary_expectation',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim'
				],
			],
			'earliest_date_of_joining' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.earliest_date_of_joining',
				'config' => [
					'dbType' => 'date',
					'type' => 'input',
					'renderType' => 'inputDateTime',
					'size' => 7,
					'eval' => 'date',
					'default' => null,
				],
			],
			'cv' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.cv',
				'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
					'cv',
					[
						'appearance' => [
							'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference'
						],
						'config' => [
							'overrideChildTca' => [
								'types' => [
									'0' => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									]
								]
							]
						],
						'maxitems' => 1
					]
				),
			],
			'cover_letter' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.cover_letter',
				'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
					'cover_letter',
					[
						'appearance' => [
							'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference'
						],
						'config' => [
							'overrideChildTca' => [
								'types' => [
									'0' => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									]
								]
							]
						],
						'maxitems' => 1
					]
				),
			],
			'testimonials' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.testimonials',
				'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
					'testimonials',
					[
						'appearance' => [
							'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference'
						],
						'config' => [
							'overrideChildTca' => [
								'types' => [
									'0' => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									]
								],
							]
						],
						'maxitems' => 1
					]
				),
			],
			'other_files' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.other_files',
				'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
					'other_files',
					[
						'appearance' => [
							'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference'
						],
						'config' => [
							'overrideChildTca' => [
								'types' => [
									'0' => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									],
									\TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
										'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
									]
								]
							]
						],
						'maxitems' => 1
					]
				),
			],
			'privacy_agreement' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.privacy_agreement',
				'config' => [
					'type' => 'check',
					'items' => [
						'1' => [
							'0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
						]
					],
					'default' => 0,
				]
			],
			'posting' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.posting',
				'config' => [
					'type' => 'select',
					'renderType' => 'selectSingle',
					'foreign_table' => 'tx_jobs_domain_model_posting',
					'minitems' => 0,
					'maxitems' => 1,
				],
			],
			"archived" => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.archived',
				'config' => [
					'type' => 'check',
					'items' => [
						'1' => [
							'0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
						]
					],
					'default' => 0,
				]
			],
			"status" => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_db.xlf:tx_jobs_domain_model_application.status',
				'config' => [
					'type' => 'select',
					'renderType' => 'selectSingle',
					'foreign_table' => 'tx_jobs_domain_model_status',
					'minitems' => 0,
					'maxitems' => 1,
				],
			]

		],
	];
