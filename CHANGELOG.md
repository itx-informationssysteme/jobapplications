# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Added privacy protection to option to send email without personal data by @hmccloy (#92)
- Added list plugin option to set prefiltered locations (#68)

### Fixed

- Improved task documentation
- Fixed error when copying posting without location requirement (#101)
- Fixed filter not working when using pagination (#90)
- Removed hidden dashboard dependency (#112 and #73)

### Changed

- Moved changelog to CHANGELOG.md
- Internal: Changed from develop and master branch to single main branch

### Removed

- Removed unnecessary TYPO3 pageNotFoundOnCHashError setting and excludeAllEmptyParameters from ext_localconf.php

## [2.2.0] - 2023-05-23 - Honeypot & Homeoffice

### Added
* Added honeypot field to application form (#81)
* Added homeoffice fields to posting (#83)

### Fixed

* Fixed non sortable contacts in contact plugin (#86)
* Fixed not being able to remove the required attribute from file uploads (#88)
* Fixed documentation formatting

## Changed
* Increased PHP version requirement to 8.0

2.1.0 - Backend module design improvements and Google for Jobs overhaul
-----------------------------------------------------------------------
* [FEATURE] added Google For Jobs "directApply" property #75
* [FEATURE] improved design of the backend module to be more consistent with TYPO3
* [FEATURE] added route enhanced URLs for Indexing API Integration #80
* [FEATURE] added region field to location record to resemble Google For Jobs #75
* [FIX] fixed and issue where the ViewHelper "<jobs:condition.inArray>" could not be resolved #71
* [FIX] restored compatibility with PHP 7.4 #65 and #66
* [FIX] fixed an issue where a missing semicolon would break script execution #76 (thank you @der-phillip)
* [FIX] fixed the value of the placeholder %postingTitle% has no output on the success page #70
* [FIX] fixed exception when using Google Indexing and creating a new job #78

2.0.0 - [BREAKING] TYPO3 11 support and new features
----------------------------------------------------
* [BREAKING] added static coding analysis via phpstan
* [FEATURE] upgrade to TYPO3 11. Removed support for older versions
* [BREAKING] removed vhs dependency
	* make sure to compare the templates, if you have overrides
* [FIX] fixed position of traits
* [BREAKING] fixed filter and reworked pagination on postings list
	* make sure to compare the templates and css, if you have overrides
* [FEATURE] added configurable page size via flexform to postings plugin
* [FIX] refactoring of google jobs structured data
* [BREAKING] replaced signal slots in favor of events
	* please change your implementations if you used signal slots previously
* [FEATURE] use new language configuration in tca
* [FIX]: fixed problems of postings filter caching in multilanguage setup
* [BREAKING]: postings can have multiple locations now
	* make sure to use the upgrade wizard to migrate entries in the location field to the new locations field and its corresponding mm table
	* make sure to compare the templates, if you have overrides
* [FEATURE]: redesigned posting tca (more place for slugs, etc)
* [FIX]: fixed error where postings could not be translated when no contact was selected

1.0.7 - Bugfixes
-----------------
* [BUGFIX] fixed plugin text about available placeholder in mail text
* [BUGFIX] fixed error in application form when no messageMaxLength is configured
* [FEATURE] show all contacts if no contact is selected in the contact plugin

1.0.6 - Build fix
-----------------
* this update contains no actual content, and only fixes a build issue

1.0.5 - Bugfixes
----------------
* [BUGFIX] fixed error "Unsupported or non-existing property name "categories" used in relation matching" when using categories with filters #51
* [BUGFIX] fixed error when removing filters from fluid template #44
* [BUGFIX] fixed and issue where filter options would not be contained to their relevant page and storage folders #28
* [FEATURE] added support for optional file uploads, you can now disable the required attribute on the file uploads #48
* [FEATURE] removed postingApp parameter from links where a detail view and an application form are on the same page, it now works automatically #41
* [FEATURE] added configurable file storage #38

1.0.4 - Skipped because of reason
---------------------------------

1.0.3 - Bugfixes
----------------
* [BUGFIX] fixed record deletion hook not checking if indexing is enabled
* [BUGFIX] fixed broken revert function in file upload file removals
* [BUGFIX] fixed php warning for some systems, when single multi file upload enabled

1.0.2 - Bugfixes
----------------
* [BUGFIX] fixed PHP warning when google key path configuration is empty (thank you @NarkNiro)
* [BUGFIX] fixed spelling mistake in translation "Arbeitsverhältnis" (thank you @chschatz)
* [BUGFIX] fixed PHP error when having legacy file upload mode enabled (thank you @thofas and @luii)
* [BUGFIX] fixed bad "Postings" plugin category filter
* [BUGFIX] fixed exception when trying to delete a hidden application #39

1.0.1 - Bugfixes
----------------
* [BUGFIX] fixed edge case where application sending would fail because of file upload (Issue #19)
* [BUGFIX] fixed wrong wrong selectize.css file contents (Issue #17)
* [BUGFIX] fixed php warning thrown when using the filter (Issue #18)

1.0.0 - [BREAKING] Better file uploads and better filters
---------------------------------------------------------
This release is one of the more feature rich releases, in this case it demanded a lot of rewriting of essential features, like
the file upload or the filter functionality. That's why this release is marked as breaking. This means especially if
you overrode templates, functionality will be broken at first and manual migrations have to take place.

As this update touched quite a lot of code there may be bugs. If you find one please report it by creating a GitHub Issue.

* [BREAKING] file uploads now work with the included JavaScript library filepond.
  This results in a different file uploading flow, where the users upload their files asynchronously. This means the user
  does not have to select the files in one file chooser window, but can do this in multiple instances and also remove each
  added file from the upload. This also improves file error handling, as the user gets feedback immediatly after the upload,
  whether the file was accepted or not.

  This change breaks the old fileuploads, meaning if you overrode the application form template, applying
  wont work anymore. To migrate, take a look into the new application form template where things like including JavaScript
  and CSS libraries and giving the upload elements a "filepond" class happened.

  Filepond gives you a lot of flexibility in its configuration, which you can find outsourced to the Application/FilepondConfig partial.
  This change also introduces the option to simply change whether the former single file uploads (cv, cover_letter...)
  are single or multi upload directly in the template. The controller will handle both.
* [BREAKING] filters got a JavaScript and functionality upgrade as well. They are now powered by selectize.js (also included).
  selectize.js requires jQuery which is also optionally included as static template.
  The main reason for using JavaScript selects, was to have good looking multi selects. This means filters can now be
  implemented in templates as single or multiselect, without changing something else.
  Apart from that, the entire filter system was reworked, now allowing custom filters to be configured via TypoScript and
  the PostingController and the Constraint model. Details on how to do this can be found in the developer section of the documentation.
* [BREAKING] the allowed file extension list was moved from the template constants to the Extension Configuration.
* [BREAKING] deprecated validThrough property in Posting domain model finally removed
* [BREAKING] changed the employment type single select into a multiselect, as google allows for multiple employment types as well
* [FEATURE] the default posting list view temlate now has the pagination viewhelper included (and overriden for working bootstrap layout),
  also including a typoscript constant allowing to set the maximum number of postings on one page
* [FEATURE] added error handler if no posting was found for the detail view
* [FEATURE] added pagination to the backend modules application entries
* [FEATURE] added smarter background routing, now skipping the dashboard view in case it was already seen in the current session
* [FEATURE] added slug behaviour extension configuration
* [BUGFIX] corrected output order in contact display
* [BUGFIX] fixed Google Indexing API implementation in v10

0.9.12 - Minor update
---------------------
* [BUGFIX] added back in the ability to send applications without uploading files
* [FEATURE] added typoscript variables for choosing ordering of postings in list view

0.9.11 - Unsolicited application error fix
------------------------------------------
* [BUGFIX] fixed error when trying to send an unsolicited application
* [BUGFIX] exluded address label from legacy email if data not in application

0.9.10 - Minor bugfixes and file upload features
------------------------------------------------
* [BUGFIX] fixed error in select in Backend Module
* [BUGFIX] fixed missing condition for the message field in the application form template
* [BUGFIX] hide empty values in posting list filter select options
* [FEATURE] added setting in extension configuration, where a custom file upload size limit can be specified
* [FEATURE] added setting in template constants where multiple allowed upload file types can be specified.
  If you use the fe.info.fileSize translation and you have an overwritten template you might want to insert a second argument allowedFileTypes
  which is provided by the controller.

0.9.9 - Bugfix for wrong hook behaviour
---------------------------------------
* [BUGFIX] fixed bug where hook was not confined to jobs posting table

0.9.8 - Bugfixes: fixed error when creating records in v9
---------------------------------------------------------
* [BUGFIX] fixed missing field error while creating records in v9 (Execute Analyse Database Tool!)
* [BUGFIX] fixed misplaced hiringOrganization in Google Jobs JSON
* [BUGFIX] when using Google Indexing API, indexing a new record was not working correctly

0.9.7 - Hotfix for wrong composer version requirements
------------------------------------------------------
* [BUGFIX] fixed typo in hiringOrganization in Google Jobs JSON
* [BUGFIX] fixed wrong composer version requirements

0.9.6 - v10 Update
------------------
This update adds support for TYPO3 v10.4 while staying backwards compatible with v9
(apart from v10 only features, which are marked as such). We made sure everything works as expected.
If you do however find a problem, please report it to us by creating a GitHub issue.

* [FEATURE] v10 only: added support for fluid email rendering
* [FEATURE] v10 only: added 2 job/application related dashboard widgets
* [FEATURE] template constant for switching email content type
* [FEATURE] added information on success page in case email sending fails (see default template for examples)
* [FEATURE] upgraded to new backend module viewhelpers
* [BUGFIX] fixed bug in contact display plugin, selection would not respect the current language
* [BUGFIX] fixed bug in plugin not respecting the storage page setting
* [BUGFIX] fixed mails in v9 not sent with html headers


0.9.5 - E-mail bugfixes
-----------------------
* [BUGFIX] fixed successAction, where no posting and salutationValue was mixed up
* [BUGFIX] fixed bug where no email was sent when only the 'send to specific mail option was selected'
* [BUGFIX] fixed bug where files where in email attachements when multifile upload was used
* [BUGFIX] fixed bug where there was still a label eventhough there was no phone number
* [FEATURE] included version 6 of vhs viewhelpers as dependency


0.9.4 - More bugfixes
---------------------
* [FEATURE] added pageData to every view
* [FEATURE] added some comments to templates
* [BUGFIX] fixed bug where it was not possible to have the EarliestDateOfJoining field could not be set as optional
* [BUGFIX] refactored successAction in ApplicationController to provide the salutation as value and to make the posting available
* [BUGFIX] fixed status import
* [BUGFIX] added asterisks for required fields in default template in application form
* [BUGFIX] fixed bug where no https:// would be generated for google indexing url
* [BUGFIX] fixed potential bug in detection of upload file field type

0.9.3 - New Features: Multiple file upload and Google Indexing API Implementation and lots of fixes
---------------------------------------------------------------------------------------------------

* [FEATURE] in this release we implemented the ability to activate a multiple file upload field.
  This does not break compatibility. You can enable this feature by going into the Template Constants and turning off the 'Use single file upload' checkbox.
  Make sure though if you have customized your template to work with 4 fields to carry over the changes of the original template.
* [FEATURE] Google Indexing API Implementation read :ref:`the documentation here <indexing-api>`. Keep in mind that this feature
  is very new and may not be stable. If you encounter any bugs or have feedback other feedback please report it on our GitHub Repo.
* [FEATURE] New button in the Backend Module on the settings page, where it is possible to trigger a batch indexing request
  for the new Google Indexing API Feature.
* [FEATURE] converted datetimes to standard TYPO3 fields starttime and endtime, so it integrates better with TYPO3
  There are 3 date fields now: "Date Posted", "Publish Date", "Valid Through".
  For more informations concerning the functionality of these fields please read the documentation :ref:`here <editors>`
  In specific cases this conversion raises some compatibility issues:

  #. Users having customized the template and used the frontend f:if viewhelper with isValid() it will work just fine as the fields and the functionality are still there
  but are deprecated and will be removed in the next version
  #. Users using the standard bootstrap layout will have to manually edit the dates in specific the "Valid Through" field as the mecanism has been removed in the frontend

* [BUGFIX] replaced tca image field definitions with more recent ones, that support image cropping
* [BUGFIX] added application backend module full unsolicited application support
* [BUGFIX] corrected label for property of cv field in standard template
* [BUGFIX] fixed bug where no applications would come up in backend module when no backend user contact relationship
* [BUGFIX] gave addition address placeholder label
* [BUGFIX] delete applicants file folder when application record deleted

0.9.2 - New  Feature & Bugfixes
-------------------------------

* application form can now be used as unsolicited application form
* fixed category loading of postings
* added category to posting model

0.9.1 - Bugfixes
----------------

* fixed bug in contact creation, when there was no backend user selected
* added header field in contact display plugin
* fixed record icons
* fixed readme links

0.9.0 - Initial beta release
----------------------------
