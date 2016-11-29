<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @copyright (c) Proud Sourcing GmbH | 2016
 * @link www.proudcommerce.com
 * @package psArticleRequest
 * @version 2.0.0
 **/

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

// ProudCommerce module description
$psModuleDesc_de = '
    Benachrichtigung bei nicht verfg&uuml;baren Artikeln, sobald diese wieder lieferbar sind.<br>
    <hr style="margin-top: 15px;">
    Aktuellste Modulversion: &nbsp; <img src="https://www.proudcommerce.com/module/version/psArticleRequest.png" border="0">
    <img src="https://www.proudcommerce.com/module/img/icon_link.png" border="0" style="width: 10px; height: 11px;">&nbsp; <a href="https://github.com/proudcommerce/psArticleRequest" target="_blank">Modul-Info</a>
';
$psModuleDesc_en = '
    Notification tool if non-deliverable item is back in stock.<br>
    <hr style="margin-top: 15px;">
    Latest module version: &nbsp; <img src="https://www.proudcommerce.com/module/version/psArticleRequest.png" border="0">
    <img src="https://www.proudcommerce.com/module/img/icon_link.png" border="0" style="width: 10px; height: 11px;">&nbsp; <a href="https://github.com/proudcommerce/psArticleRequest" target="_blank">Modul-Info</a>
';

/**
 * Module information
 */
$aModule = array(
    'id'           => 'psArticleRequest',
    'title'        => 'psArticleRequest',
    'description'  => array(
        'de' => $psModuleDesc_de,
        'en' => $psModuleDesc_en,
    ),
    'thumbnail'    => 'logo_pc-os.jpg',
    'version'      => '2.0.0',
    'author'       => 'Proud Sourcing GmbH',
    'url'          => 'http://www.proudcommerce.com/',
    'email'        => 'support@proudcommerce.com',
	'extend'       => array(
			'article_stock' => 'proudsourcing/psArticleRequest/application/controllers/admin/psarticlerequest_article_stock',
			'details'       => 'proudsourcing/psArticleRequest/application/controllers/psarticlerequest_details',
			'oxemail'       => 'proudsourcing/psArticleRequest/core/psarticlerequest_oxemail',
	),
    'files' => array(
        'psarticlerequest'                      => 'proudsourcing/psArticleRequest/application/models/psarticlerequest.php'	,
        'psarticlerequest_module'               => 'proudsourcing/psArticleRequest/core/psarticlerequest_module.php',
        'psarticlerequest_admin_main' 			=> 'proudsourcing/psArticleRequest/application/controllers/admin/psarticlerequest_admin_main.php',
        'psarticlerequest_admin_list' 			=> 'proudsourcing/psArticleRequest/application/controllers/admin/psarticlerequest_admin_list.php',
        'psarticlerequest_admin_tab_detail' 	=> 'proudsourcing/psArticleRequest/application/controllers/admin/psarticlerequest_admin_tab_detail.php'
    ),
    'templates' => array(
        'form/ps_article_request_form.tpl'              =>  'proudsourcing/psArticleRequest/application/views/flow/tpl/form/ps_article_request_form.tpl',
    	'email/ps_article_request_notification.tpl'	 	=>  'proudsourcing/psArticleRequest/application/views/flow/tpl/email/ps_article_request_notification.tpl',
    	'email/ps_article_request_customer_email.tpl' 	=>  'proudsourcing/psArticleRequest/application/views/flow/tpl/email/ps_article_request_customer_email.tpl',
    	'psarticlerequest_admin_main.tpl' 				=>  'proudsourcing/psArticleRequest/application/views/admin/tpl/psarticlerequest_admin_main.tpl',
    	'psarticlerequest_admin_list.tpl' 				=>  'proudsourcing/psArticleRequest/application/views/admin/tpl/psarticlerequest_admin_list.tpl',
    	'psarticlerequest_admin_tab_detail.tpl' 		=>  'proudsourcing/psArticleRequest/application/views/admin/tpl/psarticlerequest_admin_tab_detail.tpl',
    ),
    'blocks' => array(
        array('template' => 'page/details/inc/tabs.tpl', 'block'=>'details_tabs_media', 'file'=>'/application/views/blocks/details_tabs_media.tpl', 'position'=>100),
    ),
    'settings' => array(
        array('group' => 'psArticleRequest_config', 'name' => 'psArticleRequest_stockinfo' , 'type' => 'select', 'value' => 'man', 'position' => 15,  'constraints' => 'man|auto1|auto2'),
    ),
    'events'      => array(
        'onActivate'   => 'psarticlerequest_module::onActivate',
        'onDeactivate' => 'psarticlerequest_module::onDeactivate',
    ),
);
