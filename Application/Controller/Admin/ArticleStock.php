<?php
/**
 * @package ProudCommerce
 * @author Florian Palme <florian@proudcommerce.com>
 */

namespace ProudCommerce\ArticleRequest\Application\Controller\Admin;


use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Email;
use OxidEsales\Eshop\Core\Registry;
use ProudCommerce\ArticleRequest\Application\Model\ArticleRequest;

class ArticleStock extends ArticleStock_parent
{
    /**
     * Saves article Inventori information changes.
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function save()
    {
        if (Registry::getConfig()->getConfigParam('psArticleRequest_stockinfo') == "auto1") {
            $soxId = $this->getEditObjectId();
            /** @var Article $oArticle */
            $oArticle = oxNew(Article::class);
            $oArticle->load($soxId);
            $iOldStock = $oArticle->oxarticles__oxstock->value;
        }

        parent::save();

        if (Registry::getConfig()->getConfigParam('psArticleRequest_stockinfo') != "man") {
            $this->_getPsArticleRequests($iOldStock);
        }

    }

    /**
     * Gets open article requests and sends email to requested user when stock is updated
     * @param int $iOldStock
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    protected function _getPsArticleRequests($iOldStock = 0)
    {
        $iPsStock = 999;   // psArticleRequest_stockinfo == auto2
        if (Registry::getConfig()->getConfigParam('psArticleRequest_stockinfo') == "auto1") {
            $aParams = Registry::getRequest()->getRequestParameter("editval");
            $iNewStock = $aParams['oxarticles__oxstock'];
            $iPsStock = $iNewStock - $iOldStock;
        }

        $iCount = 1;
        $sSql = 'SELECT oxid, oxemail FROM psarticlerequest WHERE oxstatus = 1 ORDER BY OXINSERT';
        $aRequests = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC)->getAll($sSql);
        if ($iPsStock > 0 && count($aRequests) > 0) {
            foreach ($aRequests as $aRequest) {
                if ($iCount <= $iPsStock) {
                    /** @var ArticleRequest $oPsArticleRequest */
                    $oPsArticleRequest = oxNew(ArticleRequest::class);
                    if ($oPsArticleRequest->load($aRequest["oxid"])) {
                        /** @var \ProudCommerce\ArticleRequest\Core\Email $oEmail */
                        $oEmail = oxNew(Email::class);
                        if ($oEmail->sendArticleRequestToCustomer($aRequest["oxemail"], $oPsArticleRequest)) {
                            $oPsArticleRequest->psarticlerequest__oxsended->setValue(date("Y-m-d H:i:s"));
                            if ($oPsArticleRequest->psarticlerequest__oxstatus >= ArticleRequest::STATUS_SENT_NOTIFICATION) {
                                $oPsArticleRequest->psarticlerequest__oxstatus->setValue(ArticleRequest::STATUS_RESENT_NOTIFICATION);
                            } else {
                                $oPsArticleRequest->psarticlerequest__oxstatus->setValue(ArticleRequest::STATUS_SENT_NOTIFICATION);
                            }
                            $oPsArticleRequest->save();
                        }
                        $iCount++;
                    }
                }
            }
        }
    }
}