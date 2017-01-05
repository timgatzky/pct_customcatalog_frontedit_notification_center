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
namespace NotificationCenter\CustomCatalog\FrontEdit;

/**
 * Class file
 * Notifications
 *
 * Provide methods to send notifications related to PCT CustomCatalog, Frontedit plugin
 */
class Notifications extends \Controller
{
	/**
	 * 
	 */
	public function run($objPage)
	{
		if(\Input::post('FORM_SUBMIT') != \Input::post('table').'_'.\Input::post('mod') || \Input::post('table') == '')
		{
			return;
		}
		
		$strTable = \Input::post('table');
		
		// check if user sends a cc frontedit related formular
		$objCC = \CustomCatalog::findByTableName( $strTable );
		if($objCC === null)
		{
			return;
		}
		
		$objMultilanguage = new \PCT\CustomElements\Plugins\CustomCatalog\Core\Multilanguage;
		$strLanguage = $objMultilanguage->getActiveFrontendLanguage();
		
		$objEntry = $objCC->findPublishedItemByIdOrAlias(\Input::get($GLOBALS['PCT_CUSTOMCATALOG']['urlItemsParameter']),$strLanguage);

		// save, save and close
		if (\Input::post('save') != '' || \Input::post('saveNclose') != '')
		{
			// find notifications
			$objNotifications = \NotificationCenter\Model\Notification::findBy('type','cc_feedit_onsave');
			if($objNotifications === null)
			{
				return;
			}
			
			$arrTokens = array();
			$strLanguage = $GLOBALS['TL_LANGUAGE'];
		
			$arrTokens['admin_email'] = $GLOBALS['TL_ADMIN_EMAIL'];
			$arrTokens['domain'] = \Environment::get('host');
			$arrTokens['link'] = \Environment::get('base') . \Environment::get('request');
			
			// cc tokens
			$arrTokens['table'] = $strTable;
			
			// cc entry tokens  
			$arrData = $objEntry->row();
			
			$arrFormatted = array();
			foreach ($arrData as $strFieldName => $strFieldValue) 
			{
				$value = \Haste\Util\Format::dcaValue('tl_'.$strTable, $strFieldName, $strFieldValue);
			    
			    // new value from POST
			    if(isset($_POST[$strFieldName]))
			    {
				    $value = $_POST[$strFieldName];
			    }
			    
			    $arrTokens['customcatalog_entry_' . $strFieldName] = $value;
			    $arrFormatted[] = $strFieldName.': '.$value;
			}
			$arrTokens['customcatalog_entry_*'] = implode("\n",$arrFormatted);
			
			// member tokens
			$objUser = \FrontendUser::getInstance();
			$objMemberModel = \MemberModel::findByPk($objUser->id);
			if($objMemberModel !== null)
			{
				$arrFormatted = array();
				foreach ($objMemberModel->row() as $strFieldName => $strFieldValue) 
				{
					$value = \Haste\Util\Format::dcaValue('tl_member', $strFieldName, $strFieldValue);
				    $arrTokens['member_' . $strFieldName] = $value;
				    $arrFormatted[] = $strFieldName.': '.$value;
				}
				$arrTokens['member_*'] = implode("\n",$arrFormatted);
			}
			
			// send notifications
			foreach($objNotifications as $objNotification)
			{
				$objNotification->send($arrTokens,$strLanguage);
			}
		}
	}
}