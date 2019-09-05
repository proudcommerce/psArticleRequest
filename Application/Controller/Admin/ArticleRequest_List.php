<?php
/**
 * @package ProudCommerce
 * @author Florian Palme <florian@proudcommerce.com>
 */

namespace ProudCommerce\ArticleRequest\Application\Controller\Admin;


use OxidEsales\Eshop\Application\Controller\Admin\AdminlinksList;
use ProudCommerce\ArticleRequest\Application\Model\ArticleRequest;

class ArticleRequest_List extends AdminlinksList
{
    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'psarticlerequest_admin_list.tpl';

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = ArticleRequest::class;

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_sDefSortField = "oxuserid";

    /**
     * Modifying SQL query to load additional article and customer data
     *
     * @param object $oListObject list main object
     *
     * @return string
     */

    protected function _buildSelectString($oListObject = null)
    {
        $sViewName = getViewName("oxarticles", (int)$this->getConfig()->getConfigParam("sDefaultLang"));
        $sSql = "select psarticlerequest.*, {$sViewName}.oxtitle AS articletitle, ";
        $sSql .= "oxuser.oxlname as userlname, oxuser.oxfname as userfname ";
        $sSql .= "from psarticlerequest left join {$sViewName} on {$sViewName}.oxid = psarticlerequest.oxartid ";
        $sSql .= "left join oxuser on oxuser.oxid = psarticlerequest.oxuserid WHERE 1 ";

        return $sSql;
    }
}