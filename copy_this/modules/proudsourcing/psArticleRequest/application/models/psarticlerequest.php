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
class psArticleRequest extends oxBase
{
	const STATUS_RECEIVED = 1;
	const STATUS_SENT_NOTIFICATION = 2;
	const STATUS_RESENT_NOTIFICATION = 3;
	/**
	 * Current class name
	 *
	 * @var string
	 */
	protected $_sClassName = 'psarticlerequest';

	/**
	 * Article object
	 *
	 * @var object
	 */
	protected $_oArticle = null;

	/**
	 * Full article title
	 *
	 * @var string
	 */
	protected $_sTitle = null;


	/**
	 * psArticleRequest status
	 *
	 * @var int
	 */
	protected $_iStatus = null;

	/**
	 * Class constructor, initiates parent constructor (parent::oxBase()), loads
	 * base shop objects.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->init( 'psarticlerequest' );
	}

	/**
	 * Inserts object data into DB, returns true on success.
	 *
	 * @return bool
	 */
	protected function _insert()
	{
		// set oxinsert value
		$this->psarticlerequest__oxinsert = new oxField(date("Y-m-d H:i:s"));
		return parent::_insert();
	}

	/**
	 * Loads psArticleRequest article
	 *
	 * @return object
	 */
	public function getArticle()
	{
		if ( $this->_oArticle == null ) {
			$this->_oArticle = false;
			$oArticle = oxNew( "oxarticle" );
			if ( $oArticle->load($this->psarticlerequest__oxartid->value) ) {
				$this->_oArticle = $oArticle;
			}
		}
		return $this->_oArticle;
	}

	/**
	 * Returns psArticleRequest article full title
	 *
	 * @return string
	 */
	public function getTitle()
	{
		if ( $this->_sTitle == null ) {
			$this->_sTitle = false;
			if ( $oArticle = $this->getArticle() ) {
				$this->_sTitle = $oArticle->oxarticles__oxtitle->value;
				if ( $oArticle->oxarticles__oxparentid->value && !$oArticle->oxarticles__oxtitle->value) {
					$oParent = oxNew( "oxarticle" );
					$oParent->load( $oArticle->oxarticles__oxparentid->value );
					$this->_sTitle = $oParent->oxarticles__oxtitle->value . " " . $oArticle->oxarticles__oxvarselect->value;
				}
			}
		}
		return $this->_sTitle;
	}

	public function getRequestStatus() {
		return  intval(trim($this->psarticlerequest__oxstatus));
	}
}
