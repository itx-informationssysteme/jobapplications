﻿.. include:: ../Includes.txt



.. _installation:

============
Installation
============

Target group: **Administrators**

The extension is installed as any other extension.
You can install it via composer by typing

.. code-block:: bash

   composer require itx/jobapplications

or the Extension Manager in the Backend or install it locally for example directly from `Github <|project_repository|>`__.
Please make sure to switch to the Maintainance tool and push the buttons *analyse database structure* and *flush cache*.

Include Static Typoscript
-------------------------
You have to include the static typoscript file the Jobapplications extension provides.

#. Switch to the root page of your site.

#. Switch to the **Template module** and select *Info/Modify*.

#. Press the link **Edit the whole template record** and switch to the tab *Includes*.

#. Select **Jobapplications (jobapplications)** at the field *Include static (from extensions):*

#. Here you can also include the Bootstrap entry, provided by the jobapplications extension, to have a working default layout.

#. Also to have working default filters jQuery is required. If not already included elsewhere, you can include a jQuery version via a static template.

Create a secure file storage
----------------------------
If your planning to use the application form, create a secure file storage, that is not accessible from the web (if not present already). You can read here how to configure it and set it up: :ref:`Filestorage for applications <filestorage>`. This step is optional, but highly recommended.

Route Enhancers
---------------
For making the URL readable there is a file provided which can be imported in *config->sites->main->config.yaml*
or copied and modified.
This file is provided in the extension folder in *Configuration->Routes->Default.yaml*.

The provided Route Enhancers enhances the listview, detailview and applicationform.

Sitemap Example
---------------
Here is an example of a sitemap provider configuration for the extension. Fill in "xxx" with the corresponding ids.

.. code-block:: typoscript

   jobs {
	provider = TYPO3\CMS\Seo\XmlSitemap\RecordsXmlSitemapDataProvider
	config {
	   table = tx_jobapplications_domain_model_posting
	   sortField = uid
	   lastModifiedField = tstamp
	   pid = xxx
	   recursive = 0
	   url {
		  pageId = xxx
		  fieldToParameterMap {
			 uid = tx_jobapplications_detailview[posting]
		  }
		  additionalGetParameters {
			 tx_jobapplications_detailview.controller = Posting
			 tx_jobapplications_detailview.action = show
		  }
	   }
	}
   }