<?php
/**
 * @package ProudCommerce
 * @author Florian Palme <florian@proudcommerce.com>
 */

namespace ProudCommerce\ArticleRequest\Core;


use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\Registry;

class Email extends Email_parent
{
    /**
     * Email template notification info
     *
     * @var string
     */
    protected $_sArticleRequestNotificationTemplate = 'email/ps_article_request_notification.tpl';

    /**
     * Email template request info
     * @var string
     */
    protected $_sArticleRequestCustomerTemplate = 'email/ps_article_request_customer_email.tpl';

    /**
     * Send notification to user who subscribes for article request
     *
     * @param $aParams
     * @param $oArticleRequest
     * @return mixed
     */
    public function sendArticleRequestNotification($aParams, $oArticleRequest)
    {
        $this->_clearMailer();
        $oShop = $this->_getShop();

        //set mail params (from, fromName, smtp)
        $this->_setMailParams($oShop);

        $iRequestLang = $oArticleRequest->psarticlerequest__oxlang->value;

        /** @var Article $oArticle */
        $oArticle = oxNew(Article::class);
        $oArticle->loadInLang($iRequestLang, $aParams['aid']);
        $oLang = Registry::getLang();

        // create messages
        $oSmarty = $this->_getSmarty();
        $this->setViewData("product", $oArticle);

        // Process view data array through oxOutput processor
        $this->_processViewArray();
        $mailContent = $oSmarty->fetch($this->_sArticleRequestNotificationTemplate);

        $this->setRecipient($aParams['email'], $aParams['email']);
        $this->setSubject($oLang->translateString('PS_ARTICLEREQUEST_SEND_SUBJECT', $iRequestLang) . " " . $oArticle->oxarticles__oxtitle->value);
        $this->setBody($mailContent);

        return $this->send();
    }

    /**
     * Send request information with new stock to user
     *
     * @param $sRecipient
     * @param $oArticleRequest
     * @param null $sBody
     * @param null $sReturnMailBody
     * @return mixed
     */
    public function sendArticleRequestToCustomer($sRecipient, $oArticleRequest, $sBody = null, $sReturnMailBody = null)
    {
        $myConfig = $this->getConfig();
        $this->_clearMailer();

        $iRequestLang = $oArticleRequest->psarticlerequest__oxlang->value;
        $oShop = $this->_getShop($iRequestLang);

        //set mail params (from, fromName, smtp)
        $this->_setMailParams($oShop);

        //create messages
        $oLang = Registry::getLang();
        $oSmarty = $this->_getSmarty();
        $this->setViewData("shopTemplateDir", $myConfig->getTemplateDir(false));
        $oArticle = $oArticleRequest->getArticle();
        $this->setViewData("product", $oArticle);
        $this->setViewData("oArticleRequest", $oArticleRequest);

        // Process view data array through oxOutput processor
        $this->_processViewArray();

        $aStore['INCLUDE_ANY'] = $oSmarty->security_settings['INCLUDE_ANY'];
        //V send email in order language
        $iOldTplLang = $oLang->getTplLanguage();
        $iOldBaseLang = $oLang->getTplLanguage();
        $oLang->setTplLanguage($iRequestLang);
        $oLang->setBaseLanguage($iRequestLang);

        $oSmarty->security_settings['INCLUDE_ANY'] = true;
        // force non admin to get correct paths (tpl, img)
        $myConfig->setAdminMode(false);

        $this->setBody($oSmarty->fetch($this->_sArticleRequestCustomerTemplate));

        $myConfig->setAdminMode(true);
        $oLang->setTplLanguage($iOldTplLang);
        $oLang->setBaseLanguage($iOldBaseLang);
        // set it back
        $oSmarty->security_settings['INCLUDE_ANY'] = $aStore['INCLUDE_ANY'];

        $this->setRecipient($sRecipient, $sRecipient);
        $this->setSubject($oLang->translateString('PS_ARTICLEREQUEST_SEND_SUBJECT_AV', $iRequestLang) . " " . $oArticle->oxarticles__oxtitle->value);

        $this->addAddress($sRecipient, $sRecipient);
        $this->setReplyTo($oShop->oxshops__oxorderemail->value, $oShop->oxshops__oxname->getRawValue());

        if ($sReturnMailBody) {
            return $this->getBody();
        } else {
            return $this->send();
        }
    }
}