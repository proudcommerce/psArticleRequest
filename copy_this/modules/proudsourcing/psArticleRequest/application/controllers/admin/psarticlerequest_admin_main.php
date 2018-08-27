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
class psarticlerequest_admin_main extends oxAdminView
{
    protected $_sThisTemplate = 'psarticlerequest_admin_main.tpl';

    /**
     * Returns current class template name
     *
     * @return string
     */
    public function render()
    {
        parent::render();
        return $this->_sThisTemplate;
    }
}
