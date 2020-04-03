.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _changelog:

Changelog
=========
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
