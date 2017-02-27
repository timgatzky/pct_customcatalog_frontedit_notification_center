PCT CustomCatalog Frontedit Notification Center
================

About
-----
Notification Center gateway extension to send CustomCatalog Frontedit related information.

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
* ##customcatalog_entry## (Any field value of the current entry)