<?php
/**
 * @package ProudCommerce
 * @author Florian Palme <florian@proudcommerce.com>
 */

namespace ProudCommerce\ArticleRequest\Application\Controller;


use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Email;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\MailValidator;
use OxidEsales\Eshop\Core\Registry;
use ProudCommerce\ArticleRequest\Application\Model\ArticleRequest;

class ArticleDetailsController extends ArticleDetailsController_parent
{
    protected $_oCaptcha;
    protected $_iArticleRequestStatus;

    /**
     * get captcha
     * @return object|\oeCaptcha
     * @throws
     */
    public function getCaptcha()
    {
        if ($this->_oCaptcha === NULL) {
            /** @var \oeCaptcha _oCaptcha */
            $this->_oCaptcha = oxNew(\oeCaptcha::class);
        }

        return $this->_oCaptcha;
    }


    /**
     * @throws \Exception
     */
    public function request_product()
    {
        $myConfig = $this->getConfig();
        $myUtils  = Registry::getUtils();

        //control captcha
        $oCaptcha = $this->getCaptcha();
        if ( !$oCaptcha->passCaptcha() ) {
            //Registry::get("oxUtilsView")->addErrorToDisplay('MESSAGE_WRONG_VERIFICATION_CODE');
            return false;
        }

        $aParams = Registry::getRequest()->getRequestParameter( 'pa' );
        /** @var MailValidator $oMailValidator */
        $oMailValidator = oxNew(MailValidator::class);
        if(Registry::getConfig()->getActiveShop()->oxshops__oxversion->value >= 4.9) {
            if ( !isset( $aParams['email'] ) || !$oMailValidator->isValidEmail( $aParams['email'] ) ) {
                Registry::getUtilsView()->addErrorToDisplay('MESSAGE_INVALID_EMAIL');
                return false;
            }
        } else {
            // checking email address
            if ( !$oMailValidator->isValidEmail( $aParams['email'] ) ) {
                Registry::getUtilsView()->addErrorToDisplay( 'ERROR_MESSAGE_INPUT_NOVALIDEMAIL' );
                return false;
            }
        }

        $aParams['aid'] = $this->getProduct()->getId();

        /** @var ArticleRequest $oArticleRequest */
        $oArticleRequest = oxNew( ArticleRequest::class );
        $oArticleRequest->psarticlerequest__oxuserid = new Field( Registry::getSession()->getVariable( 'usr' ));
        $oArticleRequest->psarticlerequest__oxemail  = new Field( $aParams['email']);
        $oArticleRequest->psarticlerequest__oxartid  = new Field( $aParams['aid']);
        $oArticleRequest->psarticlerequest__oxshopid = new Field( $myConfig->getShopId());
        $oArticleRequest->psarticlerequest__oxlang = new Field(Registry::getLang()->getBaseLanguage());
        $oArticleRequest->psarticlerequest__oxstatus = new Field(ArticleRequest::STATUS_RECEIVED);
        $oArticleRequest->save();

        /** @var \ProudCommerce\ArticleRequest\Core\Email $oEmail */
        $oEmail = oxNew(Email::class);
        $oEmail->sendArticleRequestNotification($aParams, $oArticleRequest);

        $this->_iArticleRequestStatus = 1;
    }


    public function psArticleRequestSend()
    {
        return (int) $this->_iArticleRequestStatus;
    }
}