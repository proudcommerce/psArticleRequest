<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package ProudCommerce\ArticleRequest
 */

namespace ProudCommerce\ArticleRequest\Application\Controller\Admin;


use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Application\Model\Shop;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Email;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use ProudCommerce\ArticleRequest\Application\Model\ArticleRequest;

class ArticleRequestSettings_Tab_Detail extends AdminDetailsController
{
    protected $_sThisTemplate = 'psarticlerequest_admin_settings_tab_detail.tpl';

    public function render()
    {
        parent::render();

        $iAoc = Registry::getRequest()->getRequestParameter("aoc");
        if ($iAoc == 1) {
            $ajax = oxNew(ArticleRequestSettings_Tab_Detail_Ajax::class);
            $this->_aViewData['oxajax'] = $ajax->getColumns();

            return "psarticlerequest_admin_settings_tab_detail_ajax.tpl";
        }

        return $this->_sThisTemplate;
    }
}