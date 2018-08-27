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
class psarticlerequest_admin_tab_detail extends oxAdminDetails
{
    protected $_sThisTemplate = 'psarticlerequest_admin_tab_detail.tpl';

    public function render()
    {
        $myConfig = $this->getConfig();

        $this->addTplParam('shopid', oxRegistry::getConfig()->getShopId());

        $soxId = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            $oPsArticleRequest = oxNew("psarticlerequest");
            $oPsArticleRequest->load($soxId);

            // customer info
            $oUser = null;
            if ($oPsArticleRequest->psarticlerequest__oxuserid->value) {
                $oUser = oxNew("oxuser");
                $oUser->load($oPsArticleRequest->psarticlerequest__oxuserid->value);
                $oPsArticleRequest->oUser = $oUser;
            }

            //Load shop
            $oShop = oxNew("oxshop");
            $oShop->load($myConfig->getShopId());
            $oShop = $this->addGlobalParams($oShop);

            if (!($iLang = $oPsArticleRequest->psarticlerequest__oxlang->value)) {
                $iLang = 0;
            }

            $oLang = oxRegistry::getLang();
            $aLanguages = $oLang->getLanguageNames();
            $this->_aViewData["edit_lang"] = $aLanguages[$iLang];

            $oLetter = new stdClass();
            $aParams = oxRegistry::getConfig()->getRequestParameter("editval");

            //Get value from either admin front-end or template parse result.
            if (isset($aParams['psarticlerequest__oxlongdesc']) && $aParams['psarticlerequest__oxlongdesc']) {
                $oLetter->psarticlerequest__oxlongdesc = new oxField(stripslashes($aParams['psarticlerequest__oxlongdesc']), oxField::T_RAW);
            } else {
                $oEmail = oxNew("oxEmail");
                $sDesc = $oEmail->sendArticleRequestToCustomer($oPsArticleRequest->psarticlerequest__oxemail->value, $oPsArticleRequest, null, true);

                $iOldLang = $oLang->getTplLanguage();
                $oLang->setTplLanguage($iLang);
                $oLetter->psarticlerequest__oxlongdesc = new oxField($sDesc, oxField::T_RAW);
                $oLang->setTplLanguage($iOldLang);
            }

            $this->_aViewData["editor"] = $this->_generateTextEditor("100%", 300, $oLetter, "psarticlerequest__oxlongdesc", "details.tpl.css");
            $this->_aViewData["edit"] = $oPsArticleRequest;
            $this->_aViewData["actshop"] = $myConfig->getShopId();
        }

        parent::render();

        return $this->_sThisTemplate;
    }

    /**
     * Send email to customer to notify about product back on stock.
     */
    public function send()
    {
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");
        //If message exist, send it to customer.
        if (isset($aParams['psarticlerequest__oxlongdesc']) && $aParams['psarticlerequest__oxlongdesc']) {
            $soxId = $this->getEditObjectId();
            $oPsArticleRequest = oxNew("psarticlerequest");
            $oPsArticleRequest->load($soxId);
            $oEmail = oxNew("oxEmail");
            $blSendEmailResult = $oEmail->sendArticleRequestToCustomer($oPsArticleRequest->psarticlerequest__oxemail->value, $oPsArticleRequest, $aParams['psarticlerequest__oxlongdesc']);
            if ($blSendEmailResult) {
                $oPsArticleRequest->psarticlerequest__oxsended->setValue(date("Y-m-d H:i:s"));
                if ($oPsArticleRequest->psarticlerequest__oxstatus >= psArticleRequest::STATUS_SENT_NOTIFICATION) {
                    $oPsArticleRequest->psarticlerequest__oxstatus->setValue(psArticleRequest::STATUS_RESENT_NOTIFICATION);
                } else {
                    $oPsArticleRequest->psarticlerequest__oxstatus->setValue(psArticleRequest::STATUS_SENT_NOTIFICATION);
                }
                $oPsArticleRequest->save();
            }
        }
    }
}
