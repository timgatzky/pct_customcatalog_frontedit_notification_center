<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package form_pdf_notification_center
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$path = 'system/modules/pct_customcatalog_frontedit_notification_center';

/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'NotificationCenter',
));

/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'NotificationCenter\CustomCatalog\FrontEdit\Notifications' 			=> $path.'/NotificationCenter/CustomCatalog/FrontEdit/Notifications.php',
	'NotificationCenter\CustomCatalog\Backend\TableNcNotification' 		=> $path.'/NotificationCenter/CustomCatalog/Backend/TableNcNotification.php',
	'NotificationCenter\CustomCatalog\Backend\Notifications' 			=> $path.'/NotificationCenter/CustomCatalog/Backend/Notifications.php',
));