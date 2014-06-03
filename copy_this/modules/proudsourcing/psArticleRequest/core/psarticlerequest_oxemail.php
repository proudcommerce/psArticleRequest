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
class psArticleRequest_oxEmail extends psArticleRequest_oxEmail_parent
{
	protected $_sArticleRequestNotificationTemplate = 'email/ps_article_request_notification.tpl';
	protected $_sArticleRequestCustomerTemplate= 'email/ps_article_request_customer_email.tpl';

	/**
	 * Send notification to user who subscribes for article request
	 * @param array $aParams
	 * @param object $oArticleRequest
	 */
	public function sendArticleRequestNotification($aParams, $oArticleRequest)
    {
		$this->_clearMailer();
		$oShop = $this->_getShop();
		
		//set mail params (from, fromName, smtp)
		$this->_setMailParams( $oShop );
		
		$iRequestLang = $oArticleRequest->psarticlerequest__oxlang->value;
		
		$oArticle = oxNew( "oxarticle" );
		//$oArticle->setSkipAbPrice( true );
		$oArticle->loadInLang( $iRequestLang, $aParams['aid'] );
		$oLang = oxRegistry::getLang();
		
		// create messages
		$oSmarty = $this->_getSmarty();
		$this->setViewData( "product", $oArticle );

		// Process view data array through oxOutput processor
		$this->_processViewArray();
		$mailContent = $oSmarty->fetch( $this->_sArticleRequestNotificationTemplate ) ;

        $this->setRecipient( $aParams['email'], $aParams['email'] );
		$this->setSubject($oLang->translateString( 'PS_ARTICLEREQUEST_SEND_SUBJECT', $iRequestLang ) . " " . $oArticle->oxarticles__oxtitle->value );
		$this->setBody( $mailContent );

        return $this->send();
	}
	
	public function sendArticleRequestToCustomer( $sRecipient, $oArticleRequest, $sBody = null, $sReturnMailBody = null )
	{
		$this->_clearMailer();
	
		$oViewConfig = oxNew("oxViewConfig");
		$oShop = $this->_getShop();
		$iRequestLang = $oArticleRequest->psarticlerequest__oxlang->value;
		$oLang = oxRegistry::getLang();
		
		if ( $oShop->getId() != $oArticleRequest->psarticlerequest__oxshopid->value) {
			$oShop = oxNew( "oxshop" );
			$oShop->load( $oArticleRequest->psarticlerequest__oxshopid->value);
			$this->setShop( $oShop );
		}
	
		//set mail params (from, fromName, smtp)
		$this->_setMailParams( $oShop );
	
		// create messages
		$oSmarty = $this->_getSmarty();
	
		$oArticle =  $oArticleRequest->getArticle() ;
		$this->setViewData( "product", $oArticle);
		$this->setViewData( "oArticleRequest", $oArticleRequest );
	
		// Process view data array through oxoutput processor
		$this->_processViewArray();
	
		$this->setRecipient( $sRecipient, $sRecipient );
        $this->setSubject($oLang->translateString( 'PS_ARTICLEREQUEST_SEND_SUBJECT_AV', $iRequestLang ) . " " . $oArticle->oxarticles__oxtitle->value );
		
		if ( $sBody === null )
        {
			$sBody = $oSmarty->fetch( $this->_sArticleRequestCustomerTemplate );
		}
	
		$this->setBody( $sBody );
	
		$this->addAddress( $sRecipient, $sRecipient );
		$this->setReplyTo( $oShop->oxshops__oxorderemail->value, $oShop->oxshops__oxname->getRawValue() );
	
		if ( $sReturnMailBody ) {
			return $this->getBody();
		} else {
			return $this->send();
		}
	}
	
}