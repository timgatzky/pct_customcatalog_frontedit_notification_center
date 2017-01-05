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
namespace PCT\CustomCatalog;

/**
 * Imports
 */
use NotificationCenter\Model\Language;
use NotificationCenter\Model\Message;
#use FormPDF\FormPDFHelper;

/**
 * Class file
 * NotificationCenterGateway
 */
class NotificationCenterGateway extends \NotificationCenter\Gateway\Base implements \NotificationCenter\Gateway\GatewayInterface
{
	/**
	 * 
	 * @param object
	 * @param array
	 * @param string
	 * @return boolean
	 */
	public function send(Message $objMessage, array $arrTokens, $strLanguage = '')
	{
		$objGateway = $this->getModel();
		\PC::debug($objGateway);
		\PC::debug($arrTokens);
		throw new \Exception('--- STOP ---');
		
		
		$strPath = $GLOBALS['FORM_PDF']['path'].'/';
		if(strlen($objGateway->file_path) > 0)
		{
		   $strPath = TL_ROOT.'/'.$objGateway->file_path;
		}
		
		
		return false;
	}
}