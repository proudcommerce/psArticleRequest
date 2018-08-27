<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @copyright (c) Proud Sourcing GmbH | 2018
 * @link www.proudcommerce.com
 * @package psArticleRequest
 * @version 2.1.0
 **/
$sLangName = "Deutsch";

$aLang = [
    'charset' => 'UTF-8',

    'NAVIGATION_PSMODULE'                          => 'Proud Sourcing Module',
    'psarticlerequest'                             => 'psArticleRequest',
    'psarticlerequest_request'                     => '&Uuml;bersicht',
    'tbclpsarticlerequest_detail'                  => 'Stamm',
    'SHOP_MODULE_GROUP_psArticleRequest_config'    => 'Allgemein',
    'SHOP_MODULE_psArticleRequest_stockinfo'       => 'Lagerbestandsinfo',
    'HELP_SHOP_MODULE_psArticleRequest_stockinfo'  => '<b>manuell:</b> eMails werden nur versendet wenn man die Aktion in der psArticleRequest-Übersicht veranlasst.<br><br><b>wenn wieder Lagerbestand (nach Datum):</b> Beim Erhöhen des Bestands (z. B. +3) werden die erste drei Kunden informiert welche eine Verfügbarkeitsanfrage gestellt haben.<br><br><b>wenn wieder Lagerbestand (alle Anfrage):</b> Beim Erhöhen des Bestands im Shop-Admin werden alle Kunden informiert welche eine Verfügbarkeitsanfrage gestellt haben.<br><br><b>Cronjob:</b> Sofern der Cronjob aktiv ist werden über diesen automatisch Kunden informiert versendet, sofern ein Artikel wieder verfügbar ist.',
    'SHOP_MODULE_psArticleRequest_cronkey'         => 'Cron-Job Key',
    'HELP_SHOP_MODULE_psArticleRequest_cronkey'    => 'URL: https://shop.tld/?cl=psArticleRequest_cron&key=KEY',
    'SHOP_MODULE_psArticleRequest_minstock'        => 'Minimaler Bestand',
    'HELP_SHOP_MODULE_psArticleRequest_minstock'   => 'Wird nur beim automatischen Versand der Nachrichten per Cronjob berücksichtigt.',
    'SHOP_MODULE_psArticleRequest_stockinfo_man'   => 'manuell',
    'SHOP_MODULE_psArticleRequest_stockinfo_auto1' => 'wenn wieder Lagerbestand (nach Datum)',
    'SHOP_MODULE_psArticleRequest_stockinfo_auto2' => 'wenn wieder Lagerbestand (alle Anfragen)',
    'PS_ARTICLEREQUEST_NAME'                       => "Kunde",
    'PS_ARTICLEREQUEST_CONFIRMATION_DATE'          => "eingetragen am",
    'PS_ARTICLEREQUEST_SHIPPING_DATE'              => "informiert am",
    'PS_ARTICLEREQUEST_PRODUCT'                    => "Artikel",
    'PS_ARTICLEREQUEST_EMAIL'                      => "eMail-Adresse",
    'PS_ARTICLEREQUEST_STOCK'                      => "Aktueller Lagerbestand",
    'PS_ARTICLE_REQUEST_DETAIL_EMAILSEND'          => "Nachricht versenden",
    'PS_ARTICLE_REQUEST_LIST_MENUITEM'             => "Verfügbarkeitsanfragen",
    'PS_ARTICLE_REQUEST_LIST_MENUSUBITEM'          => "Verfügbarkeitsanfrage",
    'PS_ARTICLEREQUEST_SEND_SUBJECT_AV'            => 'Anfrage',
    'PS_ARTICLE_REQUEST_MAIL_AV1'                  => 'Wir m&ouml;chten Sie dar&uuml;ber informieren, dass der Artikel',
    'PS_ARTICLE_REQUEST_MAIL_AV2'                  => 'wieder lieferbar ist.',
    'PS_ARTICLE_REQUEST_MAIL_AV3'                  => 'Sie hatten sich am',
    'PS_ARTICLE_REQUEST_MAIL_AV4'                  => 'eingetragen, um eine Nachricht zu erhalten, sobald das Produkt wieder verf&uuml;gbar ist.',
];
