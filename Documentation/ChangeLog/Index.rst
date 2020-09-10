.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _changelog:

Changelog
=========

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

* [BREAKING] filters became a JavaScript and functionality upgrade as well. They are now powered by selectize.js (also included).
  selectize.js requires jQuery which is also optionally included as static template.
  The main reason for using JavaScript selects, was to have good looking multi selects. This means filters can now be
  implemented in templates as single or multiselect, without changing something else.
  Apart from that, the entire filter system was reworked, now allowing custom filters to be configured via TypoScript and
  the PostingController. Details on how to do this can be found in the developer section of the documentation.

* [BREAKING] the allowed file extension list was moved from the template constants to the Extension Configuration.

* [BREAKING] deprecated validThrough property in Posting domain model finally removed

* [BREAKING] changed the employment type single select into a multiselect, as google allows for multiple employment types as well

* [FEATURE] the default posting list view temlate now has the pagination viewhelper included (and overriden for working bootstrap layout),
  also including a typoscript constant allowing to set the maximum number of postings on one page

* [FEATURE] added error handler if no posting was found for the detail view

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
