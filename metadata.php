<?php

/**
 * Metadata version
 */

$sMetadataVersion = '2.0';

$psModuleDesc_de = '
    Benachrichtigung bei nicht verf&uuml;gbaren Artikeln, sobald diese wieder lieferbar sind.<br>
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
$aModule = [
    'id'          => 'psArticleRequest',
    'title'       => 'psArticleRequest',
    'description' => [
        'de' => $psModuleDesc_de,
        'en' => $psModuleDesc_en,
    ],
    'thumbnail'   => 'logo_pc-os.jpg',
    'version'     => '3.1.5',
    'author'      => 'ProudCommerce',
    'url'         => 'http://www.proudcommerce.com',
    'email'       => 'module@proudcommerce.com',
    'controllers' => [
        'psarticlerequest_cron'             => \ProudCommerce\ArticleRequest\Application\Controller\Cron::class,
        'psarticlerequest_admin_main'       => \ProudCommerce\ArticleRequest\Application\Controller\Admin\ArticleRequest_Main::class,
        'psarticlerequest_admin_list'       => \ProudCommerce\ArticleRequest\Application\Controller\Admin\ArticleRequest_List::class,
        'psarticlerequest_admin_tab_detail' => \ProudCommerce\ArticleRequest\Application\Controller\Admin\ArticleRequest_Tab_Detail::class,
        'psarticlerequest_admin_settings_main'       => \ProudCommerce\ArticleRequest\Application\Controller\Admin\ArticleRequestSettings_Main::class,
        'psarticlerequest_admin_settings_list'       => \ProudCommerce\ArticleRequest\Application\Controller\Admin\ArticleRequestSettings_List::class,
        'psarticlerequest_admin_settings_tab_detail' => \ProudCommerce\ArticleRequest\Application\Controller\Admin\ArticleRequestSettings_Tab_Detail::class,
        'psarticlerequest_admin_settings_tab_detail_ajax' => \ProudCommerce\ArticleRequest\Application\Controller\Admin\ArticleRequestSettings_Tab_Detail_Ajax::class,
    ],

    'extend' => [
        \OxidEsales\Eshop\Application\Controller\Admin\ArticleStock::class
            => \ProudCommerce\ArticleRequest\Application\Controller\Admin\ArticleStock::class,

        \OxidEsales\Eshop\Application\Controller\ArticleDetailsController::class
            => \ProudCommerce\ArticleRequest\Application\Controller\ArticleDetailsController::class,

        \OxidEsales\Eshop\Core\Email::class
            => \ProudCommerce\ArticleRequest\Core\Email::class,

        \OxidEsales\Eshop\Application\Component\Widget\ArticleDetails::class
            => \ProudCommerce\ArticleRequest\Application\Component\Widget\ArticleDetails::class,
    ],

    'templates' => [
        'form/ps_article_request_form.tpl'            => 'pc/psarticlerequest/Application/views/flow/tpl/form/ps_article_request_form.tpl',
        'email/ps_article_request_notification.tpl'   => 'pc/psarticlerequest/Application/views/flow/tpl/email/ps_article_request_notification.tpl',
        'email/ps_article_request_customer_email.tpl' => 'pc/psarticlerequest/Application/views/flow/tpl/email/ps_article_request_customer_email.tpl',
        'psarticlerequest_admin_main.tpl'             => 'pc/psarticlerequest/Application/views/admin/tpl/psarticlerequest_admin_main.tpl',
        'psarticlerequest_admin_list.tpl'             => 'pc/psarticlerequest/Application/views/admin/tpl/psarticlerequest_admin_list.tpl',
        'psarticlerequest_admin_tab_detail.tpl'       => 'pc/psarticlerequest/Application/views/admin/tpl/psarticlerequest_admin_tab_detail.tpl',
        'psarticlerequest_admin_settings_main.tpl'             => 'pc/psarticlerequest/Application/views/admin/tpl/psarticlerequest_admin_settings_main.tpl',
        'psarticlerequest_admin_settings_list.tpl'             => 'pc/psarticlerequest/Application/views/admin/tpl/psarticlerequest_admin_settings_list.tpl',
        'psarticlerequest_admin_settings_tab_detail.tpl'       => 'pc/psarticlerequest/Application/views/admin/tpl/psarticlerequest_admin_settings_tab_detail.tpl',
        'psarticlerequest_admin_settings_tab_detail_ajax.tpl'       => 'pc/psarticlerequest/Application/views/admin/tpl/psarticlerequest_admin_settings_tab_detail_ajax.tpl',
    ],

    'blocks' => [
        [
            'template' => 'page/details/inc/tabs.tpl',
            'block'    => 'details_tabs_media',
            'file'     => '/Application/views/blocks/details_tabs_media.tpl',
            'position' => 100
        ],

        [
            'template' => 'layout/page.tpl',
            'block'    => 'content_main',
            'file'     => '/Application/views/blocks/layout_page_content_main.tpl',
            'position' => 100
        ],
    ],

    'settings' => [
        [
            'group'       => 'psArticleRequest_config',
            'name'        => 'psArticleRequest_stockinfo',
            'type'        => 'select',
            'value'       => 'man',
            'position'    => 10,
            'constraints' => 'man|auto1|auto2'
        ],
        [
            'group'    => 'psArticleRequest_config',
            'name'     => 'psArticleRequest_cronkey',
            'type'     => 'str',
            'value'    => md5('psArticleRequest' . time()),
            'position' => 20
        ],
        [
            'group'    => 'psArticleRequest_config',
            'name'     => 'psArticleRequest_minstock',
            'type'     => 'str',
            'value'    => 1,
            'position' => 30
        ],
        [
            'group'    => 'psArticleRequest_config',
            'name'     => 'psArticleRequest_usevarstock',
            'type'     => 'bool',
            'value'    => false,
            'position' => 40
        ],
    ],

    'events' => [
        'onActivate'   => '\ProudCommerce\ArticleRequest\Core\Events::onActivate',
        'onDeactivate' => '\ProudCommerce\ArticleRequest\Core\Events::onDeactivate',
    ],
];
