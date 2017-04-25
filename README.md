PCT CustomCatalog Frontedit Notification Center
================

About
-----
Notification Center gateway extension to send CustomCatalog Frontedit related information.  
This extension has been funded by [Frank Schmidt EDV-Dienstleistungen](https://www.fs-edv.com) and [ES Konzepte](http://www.es-konzepte.de/)

Installation
------------
Copy the module folder to /system/modules, update database

Dependencies
------------
- Notification Center extension: https://github.com/terminal42/contao-notification_center

Usage
------------
The extension brings new notification types for CustomCatalog FrontEdit:
* When a new CC entry has been saved (on any save or save and close)
* When a new CC entry has been created (first time save)
* When a CC entry has been deleted

Available CustomCatalog related tokens:
* ##backend_link### (Backend link to the entry)
* ##backend_listview## (Backend link to the table listview)
* ##customcatalog_entry_*## (Replace * placeholder with field name for the value of the current entry)
* ##customcatalog_entry## (All field values of the current entry with linebreaks)