<?php
/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id'           => 'psArticleRequest',
    'title'        => 'psArticleRequest',
    'description'  => array(
        'de' => 'Benachrichtigung bei nicht verfg&uuml;baren Artikeln, sobald diese wieder lieferbar sind.',
        'en' => 'Notification tool if non-deliverable item is back in stock.',
    ),
    'thumbnail'    => 'logo-ps.jpg',
    'version'      => '1.0.0',
    'author'       => 'Proud Sourcing GmbH',
    'url'          => 'http://www.proudcommerce.com/',
    'email'        => 'support@proudcommerce.com',
	'extend'       => array(
			'details'       => 'proudsourcing/psArticleRequest/application/controllers/psarticlerequest_details',
			'oxemail'       => 'proudsourcing/psArticleRequest/core/psarticlerequest_oxemail',
	),
    'files' => array(
        'psarticlerequest'                      => 'proudsourcing/psArticleRequest/application/models/psarticlerequest.php'	,
        'psarticlerequest_admin_main' 			=> 'proudsourcing/psArticleRequest/application/controllers/admin/psarticlerequest_admin_main.php',
        'psarticlerequest_admin_list' 			=> 'proudsourcing/psArticleRequest/application/controllers/admin/psarticlerequest_admin_list.php',
        'psarticlerequest_admin_tab_detail' 	=> 'proudsourcing/psArticleRequest/application/controllers/admin/psarticlerequest_admin_tab_detail.php'
    ),

    'templates' => array(
        'form/ps_article_request_form.tpl'              => 'proudsourcing/psArticleRequest/application/views/azure/tpl/form/ps_article_request_form.tpl',
    	'email/ps_article_request_notification.tpl'	 	=> 'proudsourcing/psArticleRequest/application/views/azure/tpl/email/ps_article_request_notification.tpl',
    	'email/ps_article_request_customer_email.tpl' 	=> 'proudsourcing/psArticleRequest/application/views/azure/tpl/email/ps_article_request_customer_email.tpl',
    	'psarticlerequest_admin_main.tpl' 				=>  'proudsourcing/psArticleRequest/application/views/admin/tpl/psarticlerequest_admin_main.tpl',
    	'psarticlerequest_admin_list.tpl' 				=>  'proudsourcing/psArticleRequest/application/views/admin/tpl/psarticlerequest_admin_list.tpl',
    	'psarticlerequest_admin_tab_detail.tpl' 		=>  'proudsourcing/psArticleRequest/application/views/admin/tpl/psarticlerequest_admin_tab_detail.tpl',
    ),
    'blocks' => array(
        array('template' => 'page/details/inc/tabs.tpl', 'block'=>'details_tabs_media', 'file'=>'/views/blocks/details_tabs_media.tpl', 'position'=>100),
    ),
);
