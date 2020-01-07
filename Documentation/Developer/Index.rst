.. include:: ../Includes.txt


.. _developer:

================
Developer Corner
================

.. _developer-signal-slots:

Signal Slots
============
There are Signal Slots implemented which mainly happen before postings or applications are being assigned.

.. code-block:: php

   $stuff = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
      '\\Foo\\Bar\\Utility\\Stuff'
   );
   $stuff->do();

or some other language:

.. code-block:: javascript
   :linenos:
   :emphasize-lines: 2-4

   $(document).ready(
      function () {
         doStuff();
      }
   );