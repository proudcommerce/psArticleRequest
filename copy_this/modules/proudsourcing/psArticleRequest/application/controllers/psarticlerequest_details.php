<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @copyright (c) Proud Sourcing GmbH | 2016
 * @link www.proudcommerce.com
 * @package psArticleRequest
 * @version 2.0.0
 **/
class psArticleRequest_details extends psArticleRequest_details_parent
{
	public function request_product()
    {
		$myConfig = $this->getConfig();
        $myUtils  = oxRegistry::getUtils();

        //control captcha
        $sMac     = oxRegistry::getConfig()->getRequestParameter( 'c_mac' );
        $sMacHash = oxRegistry::getConfig()->getRequestParameter( 'c_mach' );
        $oCaptcha = $this->getCaptcha();
        if ( !$oCaptcha->pass( $sMac, $sMacHash ) ) {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay('MESSAGE_WRONG_VERIFICATION_CODE');
            return;
        }

        $aParams = oxRegistry::getConfig()->getRequestParameter( 'pa' );
        if(oxRegistry::getConfig()->getActiveShop()->oxshops__oxversion->value >= 4.9) {
            /** @var oxMailValidator $oMailValidator */
            $oMailValidator = oxNew('oxMailValidator');
            if ( !isset( $aParams['email'] ) || !$oMailValidator->isValidEmail( $aParams['email'] ) ) {
                oxRegistry::get("oxUtilsView")->addErrorToDisplay('MESSAGE_INVALID_EMAIL');
                return;
            }
        } else {
            // checking email address
            if ( !oxRegistry::getUtils()->isValidEmail( $aParams['email'] ) ) {
                oxRegistry::get("oxUtilsView")->addErrorToDisplay( 'ERROR_MESSAGE_INPUT_NOVALIDEMAIL' );
                return false;
	    }
        }

        $aParams['aid'] = $this->getProduct()->getId();

        $oArticleRequest = oxNew( "psarticlerequest" );
        $oArticleRequest->psarticlerequest__oxuserid = new oxField( oxRegistry::getSession()->getVariable( 'usr' ));
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
