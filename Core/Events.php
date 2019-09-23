<?php
/**
 * @package ProudCommerce
 * @author Florian Palme <florian@proudcommerce.com>
 */

namespace ProudCommerce\ArticleRequest\Core;


use OxidEsales\Eshop\Core\Base;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\DbMetaDataHandler;
use OxidEsales\Eshop\Core\Registry;

class Events extends Base
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
        $res = self::_dbEvent('install_categories.sql', 'onActivate()', 'psarticlerequest_categories;OXID');
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
     * @param string $sSqlFile SQL file located in module setup folder (usually install.sql or uninstall.sql).
     * @param string $sAction
     * @param string $sDbCheck
     * @return bool
     */
    protected static function _dbEvent($sSqlFile = "", $sAction = "", $sDbCheck = "")
    {
        if ($sSqlFile != "") {
            try {
                $oDb = DatabaseProvider::getDb();

                if (!empty($sDbCheck)) {
                    $aDbCheck = explode(";", $sDbCheck);
                    if (count($aDbCheck) > 0 && self::dbColumnExist($aDbCheck[0], $aDbCheck[1])) {
                        return true;
                    }
                }

                $sSql = file_get_contents(dirname(__FILE__) . '/../setup/sql/' . (string)$sSqlFile);
                $aSql = (array)explode(';', $sSql);
                foreach ($aSql as $sQuery) {
                    if (!empty($sQuery)) {
                        $oDb->execute($sQuery);
                    }
                }
            } catch (\Exception $ex) {
                error_log($sAction . " failed: " . $ex->getMessage());
            }

            /** @var DbMetaDataHandler $oDbHandler */
            $oDbHandler = oxNew(DbMetaDataHandler::class);
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
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public static function dbColumnExist($sTable, $sColumn)
    {
        $oDb = DatabaseProvider::getDb();
        $sDbName = Registry::getConfig()->getConfigParam('dbName');
        try {
            $sSql = "SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?";
            $blRet = $oDb->getOne($sSql, [$sDbName, $sTable, $sColumn]);
        } catch (\Exception $oEx) {
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
        $sTempFolderPath = (string) Registry::getConfig()->getConfigParam('sCompileDir');
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
        if (!in_array($sFileName, ['.', '..', '.gitkeep', '.htaccess'])) {
            if (is_file($sFilePath)) {
                @unlink($sFilePath);
            } else {
                self::clearTmp($sFilePath);
            }
        }
    }
}