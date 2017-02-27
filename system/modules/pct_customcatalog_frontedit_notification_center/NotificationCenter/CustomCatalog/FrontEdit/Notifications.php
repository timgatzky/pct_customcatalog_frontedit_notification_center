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
	 * Send notfications by customcatalog edit user actions
	 * @param object
	 * 
	 * called from getPageLayout Hook
	 */
	public function run($objPage)
	{
		if(\Input::get('table') == '' || \Input::get('act') == '')
		{
			return;
		}
		
		$strTable = \Input::get('table');
		
		// @var object CustomCatalog
		$objCC = \CustomCatalog::findByTableName( $strTable );
		if($objCC === null)
		{
			return;
		}
		
		$objMultilanguage = new \PCT\CustomElements\Plugins\CustomCatalog\Core\Multilanguage;
		$strLanguage = $objMultilanguage->getActiveFrontendLanguage();
		
		// @var object DatabaseResult
		$objEntry = $objCC->findPublishedItemByIdOrAlias(\Input::get($GLOBALS['PCT_CUSTOMCATALOG']['urlItemsParameter']),$strLanguage);
		if($objEntry->id < 1 || !isset($objEntry->id))
		{
			return;
		}
		
		$strAlias = '';
		
		// @var object SystemIntegration 
		$objSystemIntegration = new \PCT\CustomElements\Plugins\CustomCatalog\Core\SystemIntegration;
		
		// available from CC 2.2.0
		if(method_exists($objSystemIntegration, 'getBackendModuleAlias'))
		{
			$strAlias = $objSystemIntegration->getBackendModuleAlias($objCC->id);
		}
		else
		{
			$objCE = \PCT\CustomElements\Core\CustomElementFactory::findById($objCC->pid);
			if($objCE !== null)
			{
				$strAlias = sprintf($GLOBALS['PCT_CUSTOMCATALOG']['backendUrlLogic'],$objCE->get('alias'),$objCC->get('id'));
			}
		}
		
		$strAction = '';
		if(\Input::post('save') != '' || \Input::post('saveNclose') != '')
		{
			$strAction = 'save';
		}
		else if(\Input::get('act') == 'delete')
		{
			$strAction = 'delete';
		}
		
		// check if user triggers an action
		if (strlen($strAction) > 0)
		{
			$objNotifications = null;
			
			// oncreate notifications
			if($strAction == 'save' && $objEntry->tstamp < 1)
			{
				$objNotifications = \NotificationCenter\Model\Notification::findBy('type','cc_feedit_oncreate');
			}
			// onsave notifications
			else if($strAction == 'save' && $objEntry->tstamp > 0)
			{
				$objNotifications = \NotificationCenter\Model\Notification::findBy('type','cc_feedit_onsave');
			}
			// ondelete notifications
			else if($strAction == 'delete')
			{
				$objNotifications = \NotificationCenter\Model\Notification::findBy('type','cc_feedit_ondelete');
			}
			
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
			
			// backend link to table
			$arrTokens['backend_listview'] = ampersand(\Environment::get('base') . 'contao/main.php?do='.$strAlias);
			
			// backend link to entry
			$arrTokens['backend_link'] = ampersand(\Environment::get('base'). 'contao/main.php?'.http_build_query(array('do'=>$strAlias,'act'=>'edit','id'=>$objEntry->id,'rt'=>REQUEST_TOKEN,'table'=>$strTable),'','&amp;'));
			
			// cc entry tokens  
			$arrFormatted = array();
			foreach($objEntry->row() as $strFieldName => $strFieldValue) 
			{
				$value = \Haste\Util\Format::dcaValue('tl_'.$strTable, $strFieldName, $strFieldValue);
			    
			    // new value from POST
			    if(isset($_POST[$strFieldName]))
			    {
				    $value = \Input::post($strFieldName);
			    }
			    
			    $arrTokens['customcatalog_entry_' . $strFieldName] = $value;
			    $arrFormatted[] = $strFieldName.': '.$value;
			}
			$arrTokens['customcatalog_entry'] = implode("\n",$arrFormatted);
			
			// member tokens
			$objUser = \FrontendUser::getInstance();
			$objMemberModel = \MemberModel::findByPk($objUser->id);
			if($objMemberModel !== null)
			{
				$arrFormatted = array();
				foreach($objMemberModel->row() as $strFieldName => $strFieldValue) 
				{
					$value = \Haste\Util\Format::dcaValue('tl_member', $strFieldName, $strFieldValue);
				    $arrTokens['member_' . $strFieldName] = $value;
				    $arrFormatted[] = $strFieldName.': '.$value;
				}
				$arrTokens['member'] = implode("\n",$arrFormatted);
			}
			
			// send notifications
			foreach($objNotifications as $objNotification)
			{
				$objNotification->send($arrTokens,$strLanguage);
			}
		}
	}

}