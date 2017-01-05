<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2017 Leo Feyer
 *
 * @copyright 	Tim Gatzky 2017
 * @author  	Tim Gatzky <info@tim-gatzky.de>
 * @package  	pct_customcatalog_frontedit_notification_center
 * @link  		http://contao.org
 * @license  	http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Namespace
 */
namespace NotificationCenter\CustomCatalog\Backend;

/**
 * Class file
 * TableNcNotification
 */
class TableNcNotification extends \Backend
{
	/**
	 * Modify the dca
	 * @param object
	 */
	public function modifyDca($objDC)
	{
		// load language files
		\System::loadLanguageFile($objDC->table);
		
	}
}