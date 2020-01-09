.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _user-manual:

============
Users Manual
============

Basic plugin setup guide
========================

Start by creating a Folder (or more than one) to attach the Jobs Extension records to.
For this example we will use a single folder, you can split the system records into different folders as well though.
Having the folder set up we now create the pages needed for the extension to work.

Basically there are 4 Pages that each have a Jobs Plugin on them:
The *Job posting list page*, the *Detail View Page*, the *Application form page* and the *Success Page*.
Simply create all of these pages and name them as you want. You can leave them be for the moment. We will go over
each page in detail in the following section.


.. figure:: ../Images/UserManual/screen_page_tree.png
   :class: with-shadow
   :width: 300px
   :alt: Page Tree

   A proposal for the order of the pages. You can do this as you please though.


List page
---------
Let's start with the *Job posting list page*. Here you will be able to see all your postings in a list view.

Make sure you are in page mode and click *+Content*. In the popup window select
the Plugins tab and select **Jobs**.
Having done that you can give the plugin a name and then change to the *Plugin* Tab.

Here are all the settings located. First of all make sure to select the correct plugin in the *Selected Plugin* Dropdown.
On this page this is the **Jobs: Posting** Plugin.

Below that you have the setting for where you have to set the Detail Page. Simply click on the *Page* Button and select
the page you created as the detail page in the beginning.
Equally as important is to set the Record **Storage Page** (which you can find at the bottom) to the folder where you will have the job postings.
Between that there are other settings as well:

Detailview and Applicationform on same page
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Simply turn this setting on, when you want to have the Detailview and the Applicationform on the same page.
You still have to put these two plugins on the page.

Show only postings of
~~~~~~~~~~~~~~~~~~~~~
This settings allows you two filter the postings shown based on which categories they are assigned on.
This can be useful if you want to for example have an extra page for the Apprenticeship postings, so that there are no postings of another category.
You have to create category system records for this to work, which then can be referenced by the job posting record.

Detail Page
-----------
As you will have to do on every page here, you create a new plugin and go into its settings as described above already.

Make sure the correct plugin is selected. In this case it is the **Jobs: Detail View** Plugin.
Also make sure you selected the correct record storage page.

Application Module
~~~~~~~~~~~~~~~~~~
Here you can change the status of the application system being available, meaning for the end user if there is a button,
where they can get to the Application Form.

This enables the setting where you have to set the application page. That means if you dont want the application system
enabled you can stop here.

Define page title
~~~~~~~~~~~~~~~~~
In this option you can specify the page title. You have the Posting Title with the placeholder %postingTitle% available.
If left empty the default title defined by the extenion takes place.

Show contact
~~~~~~~~~~~~
This setting simply lets you decide whether you want to display the contact in the template.

Show contact photo
~~~~~~~~~~~~~~~~~~
Here you can specify if the photo of the contact should be displayed.

Show title of location
~~~~~~~~~~~~~~~~~~~~~~~
This setting provides a possibility to not show the title of the location. This is useful if you only want to show the
address of the location.

Enter a Google Maps API Key
~~~~~~~~~~~~~~~~~~~~~~~~~~~
This has no effect yet. Will come in a future update.

Application Page
----------------
This time the selected Plugin should be *Jobs: Application Form*.

Set the success page and repository the same as on the previous pages.

Define page title
~~~~~~~~~~~~~~~~~
In this option you can specify the page title. You have the Posting Title with the placeholder %postingTitle% available.
If left empty the default title defined by the extenion takes place.

Should the referenced contact receive an E-Mail?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
When this is enabled the contact which was referenced by the posting will get an E-Mail with all the information the
applicant provided including attachments.

Should new applications be sent to a specific E-Mail address?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
This does the same thing as the previous settings only that this time an E-Mail can be specified.

Should an E-Mail be sent to the applicant?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
With this setting enabled, new fields will show up, so you can enter the texts for the email.

* **E-Mail to Applicant Subject**

  Placeholder: Posting Title: %postingTitle%

* **E-Mail to Applicant Text**

  Placeholder:

  * Posting Title: %postingTitle%
  * Applicant Salutation: %applicantSalutation%
  * Applicant Firstname: %applicantFirstname%
  * Applicant Lastname: %applicantLastname%

* **E-Mail to Applicant Sender E-Mail**

  Specify which sender email the email should have assigned.

* **E-Mail to Applicant Sender Name**

  Specify the sender name the email should have assigned.

Should Applications be saved in the backend?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
When this is disabled applications are not getting persisted in the record folder you selected for this plugin.
This can be useful if you want to handle your whole application system via email.

Show "Salary Expectation" Field
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Enables or disables *Salary Expectation* Field.

Show "Earliest Date Of Joining" Field
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Enables or disables *Earliest Date Of Joining* Field.

Show "Message" field
~~~~~~~~~~~~~~~~~~~~
Enables or disables *Message* field

Maximum message number of characters:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Maximum characters for the Message field.

Link Privacy Agreement Page
~~~~~~~~~~~~~~~~~~~~~~~~~~~
Assign the Privacy Agreement page which the applicant will have to accept when he applies.
Make sure to open this in a new tab, otherwise all the applicants data will possibly be lost.

Success Page
------------
This page will be called when the applicants application was successfully sent.
The plugin here: *Jobs: Application Success Page*

The job of this plugin is to provide a success message which is personalized to the applicant.
That means it either easily integrates with other content elements on the page or the template is easily customizable
to your own needs.

Enter Message
~~~~~~~~~~~~~
Placeholders:

* Lastname: %lastName%
* Firstname: %firstName%
* Salutation: %salutation%

When the applicant entered *divers* or nothing as salutation the salutation will be automatically replaced with the
applicants first name.

Extra: Contact Plugin
=====================
There is a plugin named **Jobs: Contact Display** which simply shows the selected contacts that are defined in the plugin
settings. This can be useful to include as a section in another page.

Template Constants
==================
In the Template Constant Editor the plugin registered a few settings.

Simply select *PLUGIN.TX_JOBS*.

Change Template Path
--------------------
To override the default templates here you have the option to override the default templates.

Change CSS Path
---------------
If you want a different Bootsrap.css or even a very different .css File you can change its path here.

Enable Google Jobs
------------------
Here you can enable Google Jobs. The data for it will be automatically generated based on the posting data.
Just make sure you have selected a Company name in the Extension Configuration.

You can find the Extension Configuration under *Settings->Extension Configuration->Configure Extensions->jobs*.

.. _tasks:

Tasks
=====
There are to tasks implemented to manage applications that are not needed anymore or have to be removed for
privacy law reasons.

Both of them feature two additional settings:

#. **Age in Days**

   This determines the age the application must have.
   It is measured from the creation date of the application until now.

#. **Status Consideration**

   This settings decides whether the task should only delete applications that are in an end status
   (see :ref:`status_record` if you don't know what that means).

   This is only useful if the application administration and its
   statuses are used.

Clean up old applications
-------------------------
This task simply deletes all applications and its files, that qualified for removal. How this is determined is
described above.


Anonymize old applications
--------------------------
This task anonymizes every application, that qualified for removal information with three asterisks *\*\*\**, except
for the city name, country, privacy agreement confirmation, referenced job posting and the creation time.

This could be needed for following applicable law.

