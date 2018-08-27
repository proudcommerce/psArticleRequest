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
class psarticlerequest_article_stock extends psarticlerequest_article_stock_parent
{
    /**
     * Saves article Inventori information changes.
     */
    public function save()
    {
        if (oxRegistry::getConfig()->getConfigParam('psArticleRequest_stockinfo') == "auto1") {
            $soxId = $this->getEditObjectId();
            $oArticle = oxNew("oxarticle");
            $oArticle->load($soxId);
            $iOldStock = $oArticle->oxarticles__oxstock->value;
        }

        parent::save();

        if (oxRegistry::getConfig()->getConfigParam('psArticleRequest_stockinfo') != "man") {
            $this->_getPsArticleRequests($iOldStock);
        }

    }

    /*
     * Gets open article requests and sends email to requested user when stock is updated
     */
    protected function _getPsArticleRequests($iOldStock = 0)
    {
        $iPsStock = 999;   // psArticleRequest_stockinfo == auto2
        if (oxRegistry::getConfig()->getConfigParam('psArticleRequest_stockinfo') == "auto1") {
            $aParams = oxRegistry::getConfig()->getRequestParameter("editval");
            $iNewStock = $aParams['oxarticles__oxstock'];
            $iPsStock = $iNewStock - $iOldStock;
        }

        $iCount = 1;
        $sSql = 'SELECT oxid, oxemail FROM psarticlerequest WHERE oxstatus = 1 ORDER BY OXINSERT';
        $aRequests = oxDb::getDb(ADODB_FETCH_ASSOC)->getAll($sSql);
        if ($iPsStock > 0 && count($aRequests) > 0) {
            foreach ($aRequests as $aRequest) {
                if ($iCount <= $iPsStock) {
                    $oPsArticleRequest = oxNew("psarticlerequest");
                    if ($oPsArticleRequest->load($aRequest["oxid"])) {
                        $oEmail = oxNew("oxEmail");
                        if ($oEmail->sendArticleRequestToCustomer($aRequest["oxemail"], $oPsArticleRequest)) {
                            $oPsArticleRequest->psarticlerequest__oxsended->setValue(date("Y-m-d H:i:s"));
                            if ($oPsArticleRequest->psarticlerequest__oxstatus >= psArticleRequest::STATUS_SENT_NOTIFICATION) {
                                $oPsArticleRequest->psarticlerequest__oxstatus->setValue(psArticleRequest::STATUS_RESENT_NOTIFICATION);
                            } else {
                                $oPsArticleRequest->psarticlerequest__oxstatus->setValue(psArticleRequest::STATUS_SENT_NOTIFICATION);
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
