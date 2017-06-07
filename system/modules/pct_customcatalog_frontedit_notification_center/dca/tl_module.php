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
 * Table tl_module
 */
$objDcaHelper = \PCT\CustomElements\Plugins\CustomCatalog\Helper\DcaHelper::getInstance()->setTable('tl_module');

/**
 * Config
 */
$GLOBALS['TL_DCA'][$objDcaHelper->getTable()]['config']['onload_callback'][] = array('tl_module_pct_customcatalog_frontedit_notification_center', 'modifyDca');


/**
 * Palettes
 */
$arrPalettes = $objDcaHelper->getPalettesAsArray('customcatalogreader');
array_insert($arrPalettes,count($arrPalettes),array('notifications_legend' => array('customcatalog_edit_notification','customcatalog_edit_notification_attributes') ));
$GLOBALS['TL_DCA'][$objDcaHelper->getTable()]['palettes']['customcatalogreader'] = $objDcaHelper->generatePalettes($arrPalettes);

/**
 * Fields
 */
$objDcaHelper->addFields(array
(
	// notifications_legend
	'customcatalog_edit_notification' => array
	(
		'label'           		=> &$GLOBALS['TL_LANG']['tl_module']['customcatalog_edit_notification'],
		'exclude'         		=> true,
		'search'          		=> true,
		'inputType'       		=> 'select',
		'options_callback'		=> array('tl_module_pct_customcatalog_frontedit_notification_center','getNotifications'),
		'eval'            		=> array('tl_class'=>'','includeBlankOption'=>true,'submitOnChange'=>true,'chosen'=>true),
		'sql'			  		=> "int(10) NOT NULL default '0'",
	),
	'customcatalog_edit_notification_attributes' => $GLOBALS['TL_DCA'][$objDcaHelper->getTable()]['fields']['customcatalog_visibles'],
));

$GLOBALS['TL_DCA'][$objDcaHelper->getTable()]['fields']['customcatalog_edit_notification_attributes']['label'] = &$GLOBALS['TL_LANG']['tl_module']['customcatalog_edit_notification_attributes'];



/**
 * Class
 * tl_module_pct_customcatalog_frontedit_notification_center 
 */
class tl_module_pct_customcatalog_frontedit_notification_center extends \Backend
{
	/**
	 * Modify the dca
	 * @param object
	 */
	public function modifyDca($objDC)
	{
		if(\Input::get('act') != 'edit')
		{
			return;
		}
		
		// show notification attributes only when a "onchange" notification has been selected
		if($objDC->activeRecord === null)
		{
			$objDC->activeRecord = \ModuleModel::findByPk($objDC->id);
		}
		
		$objNotifications = \NotificationCenter\Model\Notification::findByPk($objDC->activeRecord->customcatalog_edit_notification);
		
		if($objNotifications->type != 'cc_feedit_onchange')
		{
			unset($GLOBALS['TL_DCA'][$objDC->table]['fields']['customcatalog_edit_notification_attributes']);
		}
	}
	
	
	/**
	 * Return notifications as array
	 * @param object
	 * @return array
	 */
	public function getNotifications()
	{
		$arrReturn = array();
		
		$arrTypes = array_keys($GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['pct_customcatalog_frontedit']);
		
		$objNotifications = \NotificationCenter\Model\Notification::findBy(array('FIND_IN_SET(type,?)'),implode(',',$arrTypes));
		if($objNotifications === null)
		{
			return array();
		}
		
		foreach($objNotifications as $objModel)
		{
			$arrReturn[ $objModel->id ] = $objModel->title;
		}
		
		return $arrReturn;
	}
}


