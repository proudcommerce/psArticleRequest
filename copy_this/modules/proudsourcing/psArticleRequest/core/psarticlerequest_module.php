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
class psarticlerequest_module extends oxSuperCfg
{
    /*
     * Module id
     */
    public static $sModuleId = 'psArticleRequest';


    /**
     * Module activation script.
     * @return bool
     */
    public static function onActivate()
    {
        # setup/sql/install.sql
        $res = self::_dbEvent('install.sql', 'onActivate()', 'psarticlerequest;OXID');
        return $res;
    }

    /**
     * Module deactivation script.
     */
    public static function onDeactivate()
    {
        # setup/sql/uninstall.sql
        # self::_dbEvent('uninstall.sql', 'onDeactivate()');
    }

    /**
     * Install/uninstall event.
     * Executes SQL queries form a file.
     *
     * @param string $sSqlFile      SQL file located in module setup folder (usually install.sql or uninstall.sql).
     * @param string $sFailureError An error message to show on failure.
     * @return bool
     */
    protected static function _dbEvent($sSqlFile = "", $sAction = "", $sDbCheck = "")
    {
        if($sSqlFile != "") {
            try {
                $oDb  = oxDb::getDb();

                if(!empty($sDbCheck)) {
                    $aDbCheck = explode(";", $sDbCheck);
                    if(count($aDbCheck) > 0 && self::dbColumnExist($aDbCheck[0], $aDbCheck[1])) {
                        return true;
                    }
                }

                $sSql = file_get_contents(dirname(__FILE__) . '/../setup/sql/' . (string) $sSqlFile);
                $aSql = (array) explode(';', $sSql);
                foreach ($aSql as $sQuery) {
                    if (!empty($sQuery)) {
                        $oDb->execute($sQuery);
                    }
                }
            } catch (Exception $ex) {
                error_log($sAction." failed: " . $ex->getMessage());
            }

            /** @var oxDbMetaDataHandler $oDbHandler */
            $oDbHandler = oxNew('oxDbMetaDataHandler');
            $oDbHandler->updateViews();

            self::clearTmp();
        }
        return true;
    }

    /**
     * Check if database column already exists
     *
     * @param $sTable
     * @param $sColumn
     * @return string
     */
    public static function dbColumnExist($sTable, $sColumn) {
        $oDb = oxDb::getDb();
        $sDbName = oxRegistry::getConfig()->getConfigParam('dbName');
        try {
            $sSql = "SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?";
            $blRet = $oDb->getOne($sSql, array($sDbName, $sTable, $sColumn));
        }
        catch(Exception $oEx) {
            $blRet = false;
        }
        return $blRet;
    }

    /**
     * Clean temp folder content.
     *
     * @param string $sClearFolderPath Sub-folder path to delete from. Should be a full, valid path inside temp folder.
     *
     * @return boolean
     */
    public static function clearTmp($sClearFolderPath = '')
    {
        $sFolderPath = self::_getFolderToClear($sClearFolderPath);
        $hDirHandler = opendir($sFolderPath);

        if (!empty($hDirHandler)) {
            while (false !== ($sFileName = readdir($hDirHandler))) {
                $sFilePath = $sFolderPath . DIRECTORY_SEPARATOR . $sFileName;
                self::_clear($sFileName, $sFilePath);
            }
            closedir($hDirHandler);
        }

        return true;
    }

    /**
     * Check if provided path is inside eShop `tmp/` folder or use the `tmp/` folder path.
     *
     * @param string $sClearFolderPath The folder to clear
     *
     * @return string
     */
    protected static function _getFolderToClear($sClearFolderPath = '')
    {
        $sTempFolderPath = (string) oxRegistry::getConfig()->getConfigParam('sCompileDir');
        if (!empty($sClearFolderPath) and (strpos($sClearFolderPath, $sTempFolderPath) !== false)) {
            $sFolderPath = $sClearFolderPath;
        } else {
            $sFolderPath = $sTempFolderPath;
        }
        return $sFolderPath;
    }

    /**
     * Check if resource could be deleted, then delete it's a file or
     * call recursive folder deletion if it's a directory.
     *
     * @param string $sFileName The filename to delete
     * @param string $sFilePath The path to delete
     */
    protected static function _clear($sFileName, $sFilePath)
    {
        if (!in_array($sFileName, array('.', '..', '.gitkeep', '.htaccess'))) {
            if (is_file($sFilePath)) {
                @unlink($sFilePath);
            } else {
                self::clearTmp($sFilePath);
            }
        }
    }

}