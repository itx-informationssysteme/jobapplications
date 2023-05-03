.. ---------------------------------------------------------------
   This is the start file. It gets displayed as first page
   https://docs.typo3.org/m/typo3/docs-how-to-document/master/en-us/GeneralConventions/DirectoryFilenames.html#supported-filenames-and-formats
   ---------------------------------------------------------------

.. ---------------------------------------------------------------
   More information about creating an extension manual:
   https://docs.typo3.org/m/typo3/docs-how-to-document/master/en-us/WritingDocForExtension/CreateWithExtensionBuilder.html
   ---------------------------------------------------------------

.. ---------------------------------------------------------------
   comments start with 2 dots and a blank
   they can continue on the next line
   ---------------------------------------------------------------

.. ---------------------------------------------------------------
   every .rst file should include Includes.txt
   use correct path!
   ---------------------------------------------------------------

.. include:: Includes.txt

.. ---------------------------------------------------------------
   Every manual should have a start label for cross-referencing to
   start page. Do not remove this!
   ---------------------------------------------------------------

.. _start:

.. ---------------------------------------------------------------
   This is the doctitle
   ---------------------------------------------------------------

=============================================================
Jobs Extension
=============================================================

:Extension Key:
    jobapplications

:Version:
    |release|

:Language:
    en, de

:Copyright:
    2020

:Author:
    it.x informationssysteme gmbh: Stefanie Döll, Benjamin Jasper

:Email:
    typo-itx@itx.de

:License:
   This extension documentation is published under the `GNU General Public License 3 <http://www.gnu.org/copyleft/gpl.html.>`__ (GPLv3) license

This extension provides you with the ability to create and manage job posting.

People can apply for these by using the supplied application form to have the referenced contact receive the application via
email and/or the backend module, which features a basic application management system.

See the full feature list here: :ref:`features`

**TYPO3**

The content of this document is related to TYPO3 CMS,
a GNU/GPL CMS/Framework available from `typo3.org
<https://typo3.org/>`_ .

**Community Documentation:**

This documentation is community documentation for the TYPO3 Jobapplications extension

It is maintained as part of this third party extension.

If you find an error or something is missing, please:
`Report a Problem <https://github.com/TYPO3-Documentation/TYPO3CMS-Example-ExtensionManual/issues/new>`__

**Sitemap:**

:ref:`sitemap`

.. ---------------------------------------------------------------
   This generates the menu
   https://docs.typo3.org/m/typo3/docs-how-to-document/master/en-us/WritingReST/MenuHierarchy.html
   ---------------------------------------------------------------

.. toctree::
   :maxdepth: 3
   :hidden:

   Introduction/Index
   Installation/Index
   User/Index
   Editors/Index
   Developer/Index
   Bugs/Index
   ChangeLog/Index
   Sitemap
