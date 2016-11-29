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
        $myConfig = $this->getConfig();
		$this->_clearMailer();

		$iRequestLang = $oArticleRequest->psarticlerequest__oxlang->value;
        $oShop = $this->_getShop( $iRequestLang );

        //set mail params (from, fromName, smtp)
        $this->_setMailParams( $oShop );

        //create messages
        $oLang = oxRegistry::getLang();
        $oSmarty = $this->_getSmarty();
        $this->setViewData( "shopTemplateDir", $myConfig->getTemplateDir(false) );
        $oArticle =  $oArticleRequest->getArticle() ;
        $this->setViewData( "product", $oArticle);
        $this->setViewData( "oArticleRequest", $oArticleRequest );

        // Process view data array through oxOutput processor
        $this->_processViewArray();

        $aStore['INCLUDE_ANY'] = $oSmarty->security_settings['INCLUDE_ANY'];
        //V send email in order language
        $iOldTplLang = $oLang->getTplLanguage();
        $iOldBaseLang = $oLang->getTplLanguage();
        $oLang->setTplLanguage( $iRequestLang );
        $oLang->setBaseLanguage( $iRequestLang );

        $oSmarty->security_settings['INCLUDE_ANY'] = true;
        // force non admin to get correct paths (tpl, img)
        $myConfig->setAdminMode( false );

        $this->setBody( $oSmarty->fetch( $this->_sArticleRequestCustomerTemplate ) );

        $myConfig->setAdminMode( true );
        $oLang->setTplLanguage( $iOldTplLang );
        $oLang->setBaseLanguage( $iOldBaseLang );
        // set it back
        $oSmarty->security_settings['INCLUDE_ANY'] = $aStore['INCLUDE_ANY'] ;
	
		$this->setRecipient( $sRecipient, $sRecipient );
        $this->setSubject($oLang->translateString( 'PS_ARTICLEREQUEST_SEND_SUBJECT_AV', $iRequestLang ) . " " . $oArticle->oxarticles__oxtitle->value );
	
		$this->addAddress( $sRecipient, $sRecipient );
		$this->setReplyTo( $oShop->oxshops__oxorderemail->value, $oShop->oxshops__oxname->getRawValue() );
	
		if ( $sReturnMailBody ) {
			return $this->getBody();
		} else {
			return $this->send();
		}
	}
	
}