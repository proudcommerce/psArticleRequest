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
     * Check if Turnstile module is available and active
     * @return bool
     */
    protected function isTurnstileAvailable()
    {
        return class_exists('Tabsl\Turnstile\Service\TurnstileService');
    }

    /**
     * get captcha
     * @return object|\oeCaptcha|null
     * @throws
     */
    public function getCaptcha()
    {
        if ($this->_oCaptcha === NULL) {
            if ($this->isTurnstileAvailable()) {
                // Für Turnstile geben wir null zurück, da wir den Service direkt verwenden
                $this->_oCaptcha = null;
            } else {
                // Fallback auf oxid-projects/captcha-module
                /** @var \oeCaptcha _oCaptcha */
                $this->_oCaptcha = oxNew(\oeCaptcha::class);
            }
        }

        return $this->_oCaptcha;
    }

    /**
     * Check if we should use Turnstile for this form
     * @return bool
     */
    public function shouldUseTurnstile()
    {
        if (!$this->isTurnstileAvailable()) {
            return false;
        }
        
        try {
            $turnstileService = new \Tabsl\Turnstile\Service\TurnstileService();
            // Prüfe ob Turnstile generell aktiviert ist - da es keine spezifische Methode für Article Request gibt,
            // verwenden wir eine allgemeine Prüfung
            return method_exists($turnstileService, 'isEnabledForContact') ? $turnstileService->isEnabledForContact() : false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get Turnstile Site Key for template
     * @return string
     */
    public function getTurnstileSiteKey()
    {
        if (!$this->shouldUseTurnstile()) {
            return '';
        }
        
        try {
            $turnstileService = new \Tabsl\Turnstile\Service\TurnstileService();
            if (method_exists($turnstileService, 'getSiteKey')) {
                return $turnstileService->getSiteKey();
            }
        } catch (\Exception $e) {
            // Fallback: versuche Site Key aus Config zu holen
            $siteKey = Registry::getConfig()->getConfigParam('sTurnstileSiteKey');
            return $siteKey ?: '';
        }
        
        return '';
    }

    /**
     * @throws \Exception
     */
    public function request_product()
    {
        $myConfig = $this->getConfig();
        $myUtils  = Registry::getUtils();

        //control captcha
        $bCaptchaValid = false;
        
        if ($this->shouldUseTurnstile()) {
            // Turnstile Validierung mit TurnstileService
            try {
                $turnstileService = new \Tabsl\Turnstile\Service\TurnstileService();
                $turnstileToken = Registry::getRequest()->getRequestEscapedParameter('cf-turnstile-response');
                $remoteIp = $_SERVER['REMOTE_ADDR'] ?? null;
                
                if ($turnstileToken && method_exists($turnstileService, 'verifyToken')) {
                    $bCaptchaValid = $turnstileService->verifyToken($turnstileToken, $remoteIp);
                }
            } catch (\Exception $e) {
                $bCaptchaValid = false;
            }
        } else {
            // Standard OXID CAPTCHA Validierung
            $oCaptcha = $this->getCaptcha();
            if ($oCaptcha) {
                $bCaptchaValid = $oCaptcha->passCaptcha();
            }
        }
        
        if (!$bCaptchaValid) {
            if ($this->shouldUseTurnstile()) {
                Registry::getUtilsView()->addErrorToDisplay('TURNSTILE_VERIFICATION_FAILED');
            } else {
                Registry::getUtilsView()->addErrorToDisplay('MESSAGE_WRONG_VERIFICATION_CODE');
            }
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