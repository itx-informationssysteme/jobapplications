.. include:: ../Includes.txt



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

Include Static Typoscript
-------------------------
You have to include the static typoscript file the Jobapplications extension provides.

#. Switch to the root page of your site.

#. Switch to the **Template module** and select *Info/Modify*.

#. Press the link **Edit the whole template record** and switch to the tab *Includes*.

#. Select **Jobapplications (jobapplications)** at the field *Include static (from extensions):*

#. Here you can also include the Bootstrap entry, provided by the jobapplications extension, to have a working default layout.

#. Also to have working default filters jQuery is required. If not already included elsewhere, you can include a jQuery version via a static template.

Route Enhancers
---------------
For making the URL readable there is a file provided which can be imported in *config->sites->main->config.yaml*
or copied and modified.
This file is provided in the extension folder in *Configuration->Routes->Default.yaml*.

The provided Route Enhancers enhances the listview, detailview and applicationform.