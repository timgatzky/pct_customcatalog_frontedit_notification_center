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
		
		// @var object ModuleModel
		$objModule = \ModuleModel::findByPk(\Input::post('mod'));
		
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
			$objNotification = \NotificationCenter\Model\Notification::findByPk( $objModule->customcatalog_edit_notification );
			
			// oncreate notifications
			if($strAction == 'save' && $objEntry->tstamp < 1 && $objNotification->type == 'cc_feedit_oncreate')
			{
				// all good
			}
			// onsave, onchange notifications
			else if($strAction == 'save' && $objEntry->tstamp > 0 && in_array( $objNotification->type, array('cc_feedit_onsave','cc_feedit_onchange') ))
			{
				// all good
			}
			// ondelete notifications
			else if($strAction == 'delete' && $objNotification->type == 'cc_feedit_ondelete')
			{
				// all good
			}
			else
			{
				// unknown notification
			}
			
			if($objNotification === null)
			{
				return;
			}
			
			$blnDoNotSubmit = false;
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
			
			// onchange notification
			$arrOnChange = array();
			if($objNotification->type == 'cc_feedit_onchange' && !empty($objModule->customcatalog_edit_notification_attributes))
			{
				$arrOnChange = deserialize($objModule->customcatalog_edit_notification_attributes);
			}
			
			foreach($objEntry->row() as $strFieldName => $strFieldValue) 
			{
				$value = \Haste\Util\Format::dcaValue('tl_'.$strTable, $strFieldName, $strFieldValue);
			    
			    // new value from POST
			    if(isset($_POST[$strFieldName]))
			    {
				    $value = \Input::post($strFieldName);
			    }
			    
			     // onchange notification
			    if($objNotification->type == 'cc_feedit_onchange' && in_array($strFieldName, $arrOnChange) && count($arrOnChange) > 0)
			    {
				  	// did the value change?
				  	$_post = \Input::postRaw($strFieldName);
				  	$_value = $objEntry->{$strFieldName};
				  	
				  	// binary image values
				  	if(\Validator::isBinaryUuid($_value))
				  	{
						$_value = \StringUtil::binToUuid( \FilesModel::findByUuid($_value)->uuid );
					}
					// arrays
					else if(is_array(deserialize($_value)))
					{
						$_value = deserialize($_value);
						if(!is_array($_post))
						{
							$_post = explode(',', $_post);
						}
						
						if(!array_diff($_value , $_post))
						{
							$_value = $_post = 1; // make them equal
						}
					}
				  	
				  	// skip attributes that did not change
				  	if($_post == $_value)
				  	{
					  	unset($arrOnChange[ array_search($strFieldName,$arrOnChange) ]);
					  	
					  	if($GLOBALS['PCT_CUSTOMCATALOG_FRONTEDIT_NOTIFICATION_CENTER']['onChangeShowOnlyNewValues'] === true)
					  	{
					  		continue;
					  	}
					}
				}
				// skip unselected attributes
				else if($objNotification->type == 'cc_feedit_onchange' && !in_array($strFieldName, $arrOnChange) && count($arrOnChange) > 0 && $GLOBALS['PCT_CUSTOMCATALOG_FRONTEDIT_NOTIFICATION_CENTER']['onChangeShowOnlyNewValues'] === true)
			    {
				    continue;
			    }
			    
			    // HOOK here to modify the output value
			    if (is_array($GLOBALS['CUSTOMCATALOG_FRONTEDIT_HOOKS']['notificationValue']) && count($GLOBALS['CUSTOMCATALOG_FRONTEDIT_HOOKS']) > 0)
			    {
			    	foreach($GLOBALS['CUSTOMCATALOG_FRONTEDIT_HOOKS']['notificationValue'] as $callback)
			    	{
			    		$value = \System::importStatic($callback[0])->{$callback[1]}($varValue,$objEntry,$this);
			    	}
			    }
			    
			    if($value !== null)
			    {
			        $arrTokens['customcatalog_entry_' . $strFieldName] = $value;
				    $arrFormatted[] = $strFieldName.': '.$value;
			    }
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
			
			// do not submit if nothing has changed
			if($objNotification->type == 'cc_feedit_onchange' && count($arrOnChange) < 1)
			{
				$blnDoNotSubmit = true;
			}
			
			// send notification
			if($blnDoNotSubmit === false)
			{
				$objNotification->send($arrTokens,$strLanguage);
			}
		}
	}

}