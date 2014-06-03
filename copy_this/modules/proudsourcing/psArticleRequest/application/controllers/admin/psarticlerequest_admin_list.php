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
class psarticlerequest_admin_list extends oxAdminList
{
	/**
	 * Current class template name.
	 * @var string
	 */
	protected $_sThisTemplate = 'psarticlerequest_admin_list.tpl';

	/**
	 * Name of chosen object class (default null).
	 *
	 * @var string
	 */
	protected $_sListClass = 'psarticlerequest';
	
	/**
	 * Default SQL sorting parameter (default null).
	 *
	 * @var string
	 */
	protected $_sDefSortField = "oxuserid";
	
	/**
	 * Modifying SQL query to load additional article and customer data
	 *
	 * @param object $oListObject list main object
	 *
	 * @return string
	 */
	
	protected function _buildSelectString( $oListObject = null )
	{
		$sViewName = getViewName( "oxarticles", (int) $this->getConfig()->getConfigParam( "sDefaultLang" ) );
		$sSql  = "select psarticlerequest.*, {$sViewName}.oxtitle AS articletitle, ";
		$sSql .= "oxuser.oxlname as userlname, oxuser.oxfname as userfname ";
		$sSql .= "from psarticlerequest left join {$sViewName} on {$sViewName}.oxid = psarticlerequest.oxartid ";
		$sSql .= "left join oxuser on oxuser.oxid = psarticlerequest.oxuserid WHERE 1 ";
	
		return $sSql;
	}
	
	/**
	 * Builds and returns array of SQL WHERE conditions
	 *
	 * @return array

	public function buildWhere()
	{
		$this->_aWhere = parent::buildWhere();
		$sViewName = getViewName( "psarticlerequest" );
		$sArtViewName = getViewName( "oxarticles" );
	
		// updating price fields values for correct search in DB
		if ( isset( $this->_aWhere[$sViewName.'.oxprice'] ) ) {
			$sPriceParam = (double) str_replace( array( '%', ',' ), array( '', '.' ), $this->_aWhere[$sViewName.'.oxprice'] );
			$this->_aWhere[$sViewName.'.oxprice'] = '%'. $sPriceParam. '%';
		}
	
		if ( isset( $this->_aWhere[$sArtViewName.'.oxprice'] ) ) {
			$sPriceParam = (double) str_replace( array( '%', ',' ), array( '', '.' ), $this->_aWhere[$sArtViewName.'.oxprice'] );
			$this->_aWhere[$sArtViewName.'.oxprice'] = '%'. $sPriceParam. '%';
		}
	
	
		return $this->_aWhere;
	}
     */
}