<?php
/**
 * @package ProudCommerce
 * @author Florian Palme <florian@proudcommerce.com>
 */

namespace ProudCommerce\ArticleRequest\Application\Controller\Admin;


use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Application\Model\Shop;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Email;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use ProudCommerce\ArticleRequest\Application\Model\ArticleRequest;

class ArticleRequest_Tab_Detail extends AdminDetailsController
{
    protected $_sThisTemplate = 'psarticlerequest_admin_tab_detail.tpl';

    public function render()
    {
        $myConfig = $this->getConfig();

        $this->addTplParam('shopid', Registry::getConfig()->getShopId());

        $soxId = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            /** @var ArticleRequest $oPsArticleRequest */
            $oPsArticleRequest = oxNew(ArticleRequest::class);
            $oPsArticleRequest->load($soxId);

            // customer info
            $oUser = null;
            if ($oPsArticleRequest->psarticlerequest__oxuserid->value) {
                /** @var User $oUser */
                $oUser = oxNew(User::class);
                $oUser->load($oPsArticleRequest->psarticlerequest__oxuserid->value);
                $oPsArticleRequest->oUser = $oUser;
            }

            //Load shop
            /** @var Shop $oShop */
            $oShop = oxNew(Shop::class);
            $oShop->load($myConfig->getShopId());
            $oShop = $this->addGlobalParams($oShop);

            if (!($iLang = $oPsArticleRequest->psarticlerequest__oxlang->value)) {
                $iLang = 0;
            }

            $oLang = Registry::getLang();
            $aLanguages = $oLang->getLanguageNames();
            $this->_aViewData["edit_lang"] = $aLanguages[$iLang];

            $oLetter = new \stdClass();
            $aParams = Registry::getRequest()->getRequestParameter("editval");

            //Get value from either admin front-end or template parse result.
            if (isset($aParams['psarticlerequest__oxlongdesc']) && $aParams['psarticlerequest__oxlongdesc']) {
                $oLetter->psarticlerequest__oxlongdesc = new Field(stripslashes($aParams['psarticlerequest__oxlongdesc']), Field::T_RAW);
            } else {
                /** @var \ProudCommerce\ArticleRequest\Core\Email $oEmail */
                $oEmail = oxNew(Email::class);
                $sDesc = $oEmail->sendArticleRequestToCustomer($oPsArticleRequest->psarticlerequest__oxemail->value, $oPsArticleRequest, null, true);

                $iOldLang = $oLang->getTplLanguage();
                $oLang->setTplLanguage($iLang);
                $oLetter->psarticlerequest__oxlongdesc = new Field($sDesc, Field::T_RAW);
                $oLang->setTplLanguage($iOldLang);
            }

            $this->_aViewData["editor"] = $this->generateTextEditor("100%", 300, $oLetter, "psarticlerequest__oxlongdesc", "details.tpl.css");
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
        $aParams = Registry::getRequest()->getRequestParameter("editval");
        //If message exist, send it to customer.
        if (isset($aParams['psarticlerequest__oxlongdesc']) && $aParams['psarticlerequest__oxlongdesc']) {
            $soxId = $this->getEditObjectId();
            /** @var ArticleRequest $oPsArticleRequest */
            $oPsArticleRequest = oxNew(ArticleRequest::class);
            $oPsArticleRequest->load($soxId);
            /** @var \ProudCommerce\ArticleRequest\Core\Email $oEmail */
            $oEmail = oxNew(Email::class);
            $blSendEmailResult = $oEmail->sendArticleRequestToCustomer($oPsArticleRequest->psarticlerequest__oxemail->value, $oPsArticleRequest, $aParams['psarticlerequest__oxlongdesc']);
            if ($blSendEmailResult) {
                $oPsArticleRequest->psarticlerequest__oxsended->setValue(date("Y-m-d H:i:s"));
                if ($oPsArticleRequest->psarticlerequest__oxstatus >= ArticleRequest::STATUS_SENT_NOTIFICATION) {
                    $oPsArticleRequest->psarticlerequest__oxstatus->setValue(ArticleRequest::STATUS_RESENT_NOTIFICATION);
                } else {
                    $oPsArticleRequest->psarticlerequest__oxstatus->setValue(ArticleRequest::STATUS_SENT_NOTIFICATION);
                }
                $oPsArticleRequest->save();
            } else {
                Registry::getUtilsView()->addErrorToDisplay('PS_ARTICLE_REQUEST_MAILNOTSEND');
            }
        }
    }
}