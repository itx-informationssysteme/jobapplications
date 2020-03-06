.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _changelog:

Changelog
=========
0.9.3 - New Features: Multiple file upload and Google Indexing API Implementation
----------------------------------------------------------------------------------

* [FEATURE] in this release we implemented the ability to activate a multiple file upload field.
  This does not break compatibility.
  You can enable this feature by going into the Extension Configuration and turning off the 'Use single file upload' checkbox.
  Make sure though if you have customized your template to work with 4 fields to carry over the changes of the original template.
* [FEATURE] Google Indexing API Implementation read :ref:`the documentation here <indexing-api>`. Keep in mind that this feature
  is very new and may not be stable. If you encounter any bugs or have feedback other feedback please report it on our GitHub Repo.
* [FEATURE] New button in the Backend Module on the settings page, where it is possible to trigger a batch indexing request
  for the new Google Indexing API Feature.
* [BUGFIX] replaced tca image field definitions with more recent ones, that support image cropping

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
