<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package ProudCommerce\ArticleRequest
 */

namespace ProudCommerce\ArticleRequest\Application\Controller\Admin;


use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\Eshop\Application\Controller\Admin\AdminlinksList;
use ProudCommerce\ArticleRequest\Application\Model\ArticleRequest;

class ArticleRequestSettings_List extends AdminlinksList
{
    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'psarticlerequest_admin_settings_list.tpl';
}