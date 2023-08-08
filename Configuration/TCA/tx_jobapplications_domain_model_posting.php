<?php
	$slugBehaviour = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)
														   ->get('jobapplications', 'slugBehaviour');

	return [
		'ctrl' => [
			'title' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting',
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
					'items' => [
						['', 0],
					],
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
					'items' => [
						[
							0 => '',
							1 => '',
							'invertStateDisplay' => true
						]
					],
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
					'type' => 'input',
					'renderType' => 'inputDateTime',
					'eval' => 'datetime,int',
					'default' => time(),
					'behaviour' => [
						'allowLanguageSynchronization' => true
					]
				],
			],
			'endtime' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.valid_through',
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
			'title' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.title',
				'config' => [
					'type' => 'input',
					'size' => 30,
					'eval' => 'trim,required'
				],
			],
			'date_posted' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.date_posted',
				'config' => [
					'dbType' => 'date',
					'type' => 'input',
					'renderType' => 'inputDateTime',
					'size' => 7,
					'eval' => 'date,required',
					'default' => time()
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
					'items' => [
						['LLL:EXT:jobapplications/Resources/Private/Language/locallang.xlf:fe.posting.employment.selector.fulltime', "fulltime"],
						['LLL:EXT:jobapplications/Resources/Private/Language/locallang.xlf:fe.posting.employment.selector.parttime', "parttime"],
						['LLL:EXT:jobapplications/Resources/Private/Language/locallang.xlf:fe.posting.employment.selector.contractor', "contractor"],
						['LLL:EXT:jobapplications/Resources/Private/Language/locallang.xlf:fe.posting.employment.selector.temporary', "temporary"],
						['LLL:EXT:jobapplications/Resources/Private/Language/locallang.xlf:fe.posting.employment.selector.intern', "intern"],
						['LLL:EXT:jobapplications/Resources/Private/Language/locallang.xlf:fe.posting.employment.selector.volunteer', "volunteer"],
						['LLL:EXT:jobapplications/Resources/Private/Language/locallang.xlf:fe.posting.employment.selector.perdiem', "perdiem"],
						['LLL:EXT:jobapplications/Resources/Private/Language/locallang.xlf:fe.posting.employment.selector.other', "other"]
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
				'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
					'detail_view_image',
					[
						'appearance' => [
							'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference',
							'showPossibleLocalizationRecords' => true,
							'showRemovedLocalizationRecords' => true,
							'showAllLocalizationLink' => true,
							'showSynchronizationLink' => true
						],
						'maxitems' => 1,
						'overrideChildTca' => ['types' => [
							'0' => [
								'showitem' => '
								--palette--;;imageoverlayPalette,
								--palette--;;filePalette'
							],
							\TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
								'showitem' => '
								--palette--;;imageoverlayPalette,
								--palette--;;filePalette'
							],
							\TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
								'showitem' => '
								--palette--;;imageoverlayPalette,
								--palette--;;filePalette'
							],
							\TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
								'showitem' => '
								--palette--;;imageoverlayPalette,
								--palette--;;filePalette'
							],
							\TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
								'showitem' => '
								--palette--;;imageoverlayPalette,
								--palette--;;filePalette'
							],
							\TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
								'showitem' => '
								--palette--;;imageoverlayPalette,
								--palette--;;filePalette'
							]
						]]
					],
					$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
				),
			],
			'list_view_image' => [
				'exclude' => true,
				'label' => 'LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.list_view_image',
				'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
					'list_view_image',
					[
						'appearance' => [
							'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference',
							'showPossibleLocalizationRecords' => true,
							'showRemovedLocalizationRecords' => true,
							'showAllLocalizationLink' => true,
							'showSynchronizationLink' => true
						],
						'maxitems' => 1,
						'overrideChildTca' => ['types' => [
							'0' => [
								'showitem' => '
								--palette--;;imageoverlayPalette,
								--palette--;;filePalette'
							],
							\TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
								'showitem' => '
								--palette--;;imageoverlayPalette,
								--palette--;;filePalette'
							],
							\TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
								'showitem' => '
								--palette--;;imageoverlayPalette,
								--palette--;;filePalette'
							],
							\TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
								'showitem' => '
								--palette--;;imageoverlayPalette,
								--palette--;;filePalette'
							],
							\TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
								'showitem' => '
								--palette--;;imageoverlayPalette,
								--palette--;;filePalette'
							],
							\TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
								'showitem' => '
								--palette--;;imageoverlayPalette,
								--palette--;;filePalette'
							]
						]]
					],
					$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
				),
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
					'default' => 0,
					'renderType' => 'selectSingle',
					'foreign_table' => 'tx_jobapplications_domain_model_locationrequirement',
					'foreign_table_where' => 'tx_jobapplications_domain_model_locationrequirement.sys_language_uid IN (0,-1) ORDER BY tx_jobapplications_domain_model_locationrequirement.name ASC',
					'minitems' => 0,
					'maxitems' => 1,
					'eval' => 'required'
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
					'default' => ''
				],
			],
		],
		'types' => [
			'1' => [
				'showitem' => '--palette--;;mainInfo,--palette--;;relations,--palette--;;dates,--palette--;;circumstances, --palette--;;homeoffice-settings, --div--;LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.title.advanced,--palette--;;general,--div--;LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.title.texts,company_description,job_description,role_description,skill_requirements,benefits,required_documents,company_information,--div--;LLL:EXT:jobapplications/Resources/Private/Language/locallang_db.xlf:tx_jobapplications_domain_model_posting.title.images,--palette--;;images'
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
