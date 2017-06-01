<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @copyright	Tim Gatzky 2017
 * @author		Tim Gatzky <info@tim-gatzky.de>
 * @package		form_pdf_notification_center
 * @link		http://contao.org
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['getPageLayout'][] = array('NotificationCenter\CustomCatalog\FrontEdit\Notifications','run');

/**
 * Notification center notification types
 */
$arrTokens1 = array('domain', 'link', 'table','backend_listview','backend_link','member', 'member_*', 'admin_email','customcatalog_entry', 'customcatalog_entry_*');

// Register tokens
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['pct_customcatalog_frontedit'] = array
(
	'cc_feedit_onsave' => array
	(
        'recipients'           => array('member_email', 'admin_email','customcatalog_entry_*'),
        'email_subject'        => $arrTokens1,
        'email_text'           => $arrTokens1,
        'email_html'           => $arrTokens1,
        'email_sender_name'    => array('admin_email', 'member_*', 'customcatalog_entry_*'),
        'email_sender_address' => array('admin_email', 'member_*', 'customcatalog_entry_*' ),
        'email_recipient_cc'   => array('admin_email', 'member_*', 'customcatalog_entry_*' ),
        'email_recipient_bcc'  => array('admin_email', 'member_*', 'customcatalog_entry_*' ),
        'email_replyTo'        => array('admin_email', 'member_*', 'customcatalog_entry_*' ),
    ),
    'cc_feedit_oncreate' => array
	(
        'recipients'           => array('member_email', 'admin_email','customcatalog_entry_*'),
        'email_subject'        => $arrTokens1,
        'email_text'           => $arrTokens1,
        'email_html'           => $arrTokens1,
        'email_sender_name'    => array('admin_email', 'member_*', 'customcatalog_entry_*'),
        'email_sender_address' => array('admin_email', 'member_*', 'customcatalog_entry_*' ),
        'email_recipient_cc'   => array('admin_email', 'member_*', 'customcatalog_entry_*' ),
        'email_recipient_bcc'  => array('admin_email', 'member_*', 'customcatalog_entry_*' ),
        'email_replyTo'        => array('admin_email', 'member_*', 'customcatalog_entry_*' ),
    ),
    'cc_feedit_ondelete' => array
	(
        'recipients'           => array('member_email', 'admin_email','customcatalog_entry_*'),
        'email_subject'        => $arrTokens1,
        'email_text'           => $arrTokens1,
        'email_html'           => $arrTokens1,
        'email_sender_name'    => array('admin_email', 'member_*', 'customcatalog_entry_*'),
        'email_sender_address' => array('admin_email', 'member_*', 'customcatalog_entry_*' ),
        'email_recipient_cc'   => array('admin_email', 'member_*', 'customcatalog_entry_*' ),
        'email_recipient_bcc'  => array('admin_email', 'member_*', 'customcatalog_entry_*' ),
        'email_replyTo'        => array('admin_email', 'member_*', 'customcatalog_entry_*' ),
    ),
    'cc_feedit_onchange' => array
	(
        'recipients'           => array('member_email', 'admin_email','customcatalog_entry_*'),
        'email_subject'        => $arrTokens1,
        'email_text'           => $arrTokens1,
        'email_html'           => $arrTokens1,
        'email_sender_name'    => array('admin_email', 'member_*', 'customcatalog_entry_*'),
        'email_sender_address' => array('admin_email', 'member_*', 'customcatalog_entry_*' ),
        'email_recipient_cc'   => array('admin_email', 'member_*', 'customcatalog_entry_*' ),
        'email_recipient_bcc'  => array('admin_email', 'member_*', 'customcatalog_entry_*' ),
        'email_replyTo'        => array('admin_email', 'member_*', 'customcatalog_entry_*' ),
    ),
);

