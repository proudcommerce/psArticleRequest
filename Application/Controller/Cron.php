<?php
/**
 * @package ProudCommerce
 * @author Florian Palme <florian@proudcommerce.com>
 */

namespace ProudCommerce\ArticleRequest\Application\Controller;


use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Email;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Setup\Database;
use ProudCommerce\ArticleRequest\Application\Model\ArticleRequest;

class Cron extends FrontendController
{
    /**
     * Cronjob key
     *
     * @var string
     */
    protected $_sCronKey = 'oJzoRK6373A8kt8mcQfKg3Lpw7zZcELHYty';

    /**
     * Minimal stock for information
     *
     * @var string
     */
    protected $_iMinStock = 0;

    /**
     * psArticleRequest_cron constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->_sCronKey = Registry::getConfig()->getConfigParam('psArticleRequest_cronkey');
        if (!$this->_checkAuthorization()) {
            echo '<pre>Authentifizierung fehlgeschlagen!</pre>';
            exit;
        }
        $this->_iMinStock = Registry::getConfig()->getConfigParam('psArticleRequest_minstock');
    }


    /**
     * Render template
     *
     * @return null|void
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function render()
    {
        parent::render();

        $sMsg = '';
        $aArticles = $this->_getRequestedArticles();
        foreach ($aArticles as $key => $value) {
            $sMsg .= '<br><b>' . $value['artnum'] . '</b><br>';
            $sMsg .= 'Bestand: ' . $value['stock'] . ' (Mindestbestand: ' . $this->_iMinStock . ')<br>';
            if ($value['stock'] > 0 && $value['stock'] > $this->_iMinStock) {
                $sMsg .= $this->sendCustomerMail($key);
            }
        }

        echo '<pre>' . $sMsg . '</pre>';
        exit;
    }

    /**
     * Get requested articles as array
     *
     * @param $sOxid
     * @return string
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    protected function sendCustomerMail($sOxid)
    {
        $sMsg = '';
        $sSql = 'SELECT oxid FROM psarticlerequest WHERE oxsended IS NULL AND oxartid = ' . DatabaseProvider::getDb()->quote($sOxid);
        $results = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC)->getCol($sSql);
        foreach($results as $oxid) {
            $oPsArticleRequest = oxNew(ArticleRequest::class);
            if ($oPsArticleRequest->load($oxid)) {
                /** @var \ProudCommerce\ArticleRequest\Core\Email $oEmail */
                $oEmail = oxNew(Email::class);
                if ($oEmail->sendArticleRequestToCustomer($oPsArticleRequest->psarticlerequest__oxemail->value, $oPsArticleRequest, null, false)) {
                    $sMsg .= $oPsArticleRequest->psarticlerequest__oxemail->value . ' - OK<br>';
                    $oPsArticleRequest->psarticlerequest__oxsended->setValue(date('Y-m-d H:i:s'));
                    if ($oPsArticleRequest->psarticlerequest__oxstatus >= ArticleRequest::STATUS_SENT_NOTIFICATION) {
                        $oPsArticleRequest->psarticlerequest__oxstatus->setValue(ArticleRequest::STATUS_RESENT_NOTIFICATION);
                    } else {
                        $oPsArticleRequest->psarticlerequest__oxstatus->setValue(ArticleRequest::STATUS_SENT_NOTIFICATION);
                    }
                    $oPsArticleRequest->save();
                } else {
                    $sMsg .= $oPsArticleRequest->psarticlerequest__oxemail->value . ' - ERROR<br>';
                }
            }
        }
        return $sMsg;
    }

    /**
     * Get requested articles as array
     *
     * @return array
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    protected function _getRequestedArticles()
    {
        $aArticles = [];
        $sSql = 'SELECT oxartid FROM psarticlerequest WHERE oxsended IS NULL GROUP BY oxartid';
        $result = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC)->getCol($sSql);
        foreach ($result as $artid) {
            /** @var Article $oArticle */
            $oArticle = oxNew(Article::class);
            if ($oArticle->load($artid)) {
                $aArticles[$artid] = ['artnum' => $oArticle->oxarticles__oxartnum->value, 'stock' => $oArticle->oxarticles__oxstock->value + (Registry::getConfig()->getConfigParam('psArticleRequest_usevarstock') ? $oArticle->oxarticles__oxvarstock->value : 0)];
            }
        }
        return $aArticles;
    }

    /**
     * Check cronjob key
     *
     * @return  boolean     key true/false
     */
    protected function _checkAuthorization()
    {
        $sKeyView = Registry::getRequest()->getRequestParameter('key');
        if (!empty($sKeyView) && $this->_sCronKey == $sKeyView) {
            return true;
        }
        return false;
    }
}