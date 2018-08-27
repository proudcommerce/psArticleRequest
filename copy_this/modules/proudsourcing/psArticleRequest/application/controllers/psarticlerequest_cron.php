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
class psArticleRequest_cron extends oxUBase
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
        $this->_sCronKey = oxRegistry::getConfig()->getConfigParam('psArticleRequest_cronkey');
        if (!$this->_checkAuthorization()) {
            echo '<pre>Authentifizierung fehlgeschlagen!</pre>';
            exit;
        }
        $this->_iMinStock = oxRegistry::getConfig()->getConfigParam('psArticleRequest_minstock');
    }


    /**
     * Render template
     *
     * @return null|void
     * @throws oxConnectionException
     * @throws oxSystemComponentException
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
     * @throws oxConnectionException
     * @throws oxSystemComponentException
     */
    protected function sendCustomerMail($sOxid)
    {
        $sMsg = '';
        $sSql = 'SELECT oxid FROM psarticlerequest WHERE oxsended IS NULL AND oxartid = ' . oxDb::getDb()->quote($sOxid);
        $rs = oxDb::getDb(oxDb::FETCH_MODE_ASSOC)->execute($sSql);
        if ($rs != false && $rs->recordCount() > 0) {
            while (!$rs->EOF) {
                $oPsArticleRequest = oxNew('psarticlerequest');
                if ($oPsArticleRequest->load($rs->fields['oxid'])) {
                    $oEmail = oxNew('oxEmail');
                    if ($oEmail->sendArticleRequestToCustomer($oPsArticleRequest->psarticlerequest__oxemail->value, $oPsArticleRequest, null, false)) {
                        $sMsg .= $oPsArticleRequest->psarticlerequest__oxemail->value . ' - OK<br>';
                        $oPsArticleRequest->psarticlerequest__oxsended->setValue(date('Y-m-d H:i:s'));
                        if ($oPsArticleRequest->psarticlerequest__oxstatus >= psArticleRequest::STATUS_SENT_NOTIFICATION) {
                            $oPsArticleRequest->psarticlerequest__oxstatus->setValue(psArticleRequest::STATUS_RESENT_NOTIFICATION);
                        } else {
                            $oPsArticleRequest->psarticlerequest__oxstatus->setValue(psArticleRequest::STATUS_SENT_NOTIFICATION);
                        }
                        $oPsArticleRequest->save();
                    } else {
                        $sMsg .= $oPsArticleRequest->psarticlerequest__oxemail->value . ' - ERROR<br>';
                    }
                }
                $rs->moveNext();
            }
        }
        return $sMsg;
    }

    /**
     * Get requested articles as array
     *
     * @return array
     * @throws oxConnectionException
     * @throws oxSystemComponentException
     */
    protected function _getRequestedArticles()
    {
        $aArticles = [];
        $sSql = 'SELECT oxartid FROM psarticlerequest WHERE oxsended IS NULL GROUP BY oxartid';
        $rs = oxDb::getDb(oxDb::FETCH_MODE_ASSOC)->execute($sSql);
        if ($rs != false && $rs->recordCount() > 0) {
            while (!$rs->EOF) {
                $oArticle = oxNew('oxarticle');
                if ($oArticle->load($rs->fields['oxartid'])) {
                    $aArticles[$rs->fields['oxartid']] = ['artnum' => $oArticle->oxarticles__oxartnum->value, 'stock' => $oArticle->oxarticles__oxstock->value];
                }
                $rs->moveNext();
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
        $sKeyView = oxRegistry::getConfig()->getRequestParameter('key');
        if (!empty($sKeyView) && $this->_sCronKey == $sKeyView) {
            return true;
        }
        return false;
    }

}
