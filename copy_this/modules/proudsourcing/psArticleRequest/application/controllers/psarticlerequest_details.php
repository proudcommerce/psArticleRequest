<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @copyright (c) Proud Sourcing GmbH | 2014
 * @link www.proudcommerce.com
 * @package psArticleRequest
 * @version 1.0.0
 **/
class psArticleRequest_details extends psArticleRequest_details_parent
{
	public function request_product()
    {
		$myConfig = $this->getConfig();
        $myUtils  = oxRegistry::getUtils();

        //control captcha
        $sMac     = oxConfig::getParameter( 'c_mac' );
        $sMacHash = oxConfig::getParameter( 'c_mach' );
        $oCaptcha = $this->getCaptcha();
        if ( !$oCaptcha->pass( $sMac, $sMacHash ) ) {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay('MESSAGE_WRONG_VERIFICATION_CODE');
            return;
        }

        $aParams = oxConfig::getParameter( 'pa' );
        if ( !isset( $aParams['email'] ) || !$myUtils->isValidEmail( $aParams['email'] ) ) {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay('MESSAGE_INVALID_EMAIL');
            return;
        }
        $aParams['aid'] = $this->getProduct()->getId();

        $oArticleRequest = oxNew( "psarticlerequest" );
        $oArticleRequest->psarticlerequest__oxuserid = new oxField( oxSession::getVar( 'usr' ));
        $oArticleRequest->psarticlerequest__oxemail  = new oxField( $aParams['email']);
        $oArticleRequest->psarticlerequest__oxartid  = new oxField( $aParams['aid']);
        $oArticleRequest->psarticlerequest__oxshopid = new oxField( $myConfig->getShopId());
        $oArticleRequest->psarticlerequest__oxlang = new oxField(oxRegistry::getLang()->getBaseLanguage());
        $oArticleRequest->psarticlerequest__oxstatus = new oxField(psArticleRequest::STATUS_RECEIVED);
        $oArticleRequest->save();

        $oEmail = oxNew("oxEmail");
        $oEmail->sendArticleRequestNotification($aParams, $oArticleRequest);

        $this->_iArticleRequestStatus = 1;
        oxRegistry::get("oxUtilsView")->addErrorToDisplay('PS_ARTICLEREQUEST_SUCCESS');
	}

}
