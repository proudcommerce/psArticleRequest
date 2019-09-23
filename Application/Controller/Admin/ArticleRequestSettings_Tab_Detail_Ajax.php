<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package ProudCommerce\ArticleRequest
 */

namespace ProudCommerce\ArticleRequest\Application\Controller\Admin;


use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\Registry;

class ArticleRequestSettings_Tab_Detail_Ajax extends \OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax
{
    /**
     * Columns array
     *
     * @var array
     */
    protected $_aColumns = ['container1' => [ // field , table,         visible, multilanguage, ident
                                              ['oxtitle', 'oxcategories', 1, 1, 0],
                                              ['oxdesc', 'oxcategories', 1, 1, 0],
                                              ['oxid', 'oxcategories', 0, 0, 0],
                                              ['oxid', 'oxcategories', 0, 0, 1]
    ],
                            'container2' => [
                                ['oxtitle', 'oxcategories', 1, 1, 0],
                                ['oxdesc', 'oxcategories', 1, 1, 0],
                                ['oxid', 'oxcategories', 0, 0, 0],
                                ['oxid', 'psarticlerequest_categories', 0, 0, 1],
                                ['oxid', 'oxcategories', 0, 0, 1]
                            ],
    ];

    /**
     * Returns SQL query for data to fetc
     *
     * @return string
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    protected function _getQuery()
    {
        $categoriesTable = $this->_getViewName('oxcategories');
        $objectToCategoryView = $this->_getViewName('psarticlerequest_categories');
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        $oxId = Registry::getRequest()->getRequestParameter('oxid');
        $synchOxid = Registry::getRequest()->getRequestParameter('synchoxid');

        if ($oxId) {
            // all categories article is in
            $query = " from $objectToCategoryView left join $categoriesTable on $categoriesTable.oxid=$objectToCategoryView.oxcatnid ";
            $query .= " where "
                      . " $categoriesTable.oxid is not null ";
        } else {
            $query = " from $categoriesTable where $categoriesTable.oxid not in ( ";
            $query .= " select $categoriesTable.oxid from $objectToCategoryView "
                      . "left join $categoriesTable on $categoriesTable.oxid=$objectToCategoryView.oxcatnid ";
            $query .= " where "
                      . " $categoriesTable.oxid is not null ) and $categoriesTable.oxpriceto = '0'";
        }

        return $query;
    }

    /**
     * Returns array with DB records
     *
     * @param string $sQ SQL query
     *
     * @return array
     */
    protected function _getDataFields($sQ)
    {
        $dataFields = parent::_getDataFields($sQ);

        return $dataFields;
    }

    /**
     * Removes article from chosen category
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function removeCat()
    {
        $categoriesToRemove = $this->_getActionIds('oxcategories.oxid');

        $oxId = Registry::getRequest()->getRequestParameter('oxid');
        $dataBase = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        // adding
        if (Registry::getRequest()->getRequestParameter('all')) {
            $categoriesTable = $this->_getViewName('oxcategories');
            $categoriesToRemove = $this->_getAll($this->_addFilter("select {$categoriesTable}.oxid " . $this->_getQuery()));
        }

        // removing all
        if (is_array($categoriesToRemove) && count($categoriesToRemove)) {
            $query = "delete from psarticlerequest_categories where ";
            $query = $this->updateQueryForRemovingArticleFromCategory($query);
            $query .= " oxcatnid in (" . implode(', ', \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->quoteArray($categoriesToRemove)) . ')';
            $dataBase->execute($query);

        }

        $this->onCategoriesRemoval($categoriesToRemove, $oxId);
    }

    /**
     * Adds article to chosen category
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \Exception
     */
    public function addCat()
    {
        $config = $this->getConfig();
        $categoriesToAdd = $this->_getActionIds('oxcategories.oxid');
        $oxId = Registry::getRequest()->getRequestParameter('synchoxid');
        $shopId = $config->getShopId();
        $objectToCategoryView = $this->_getViewName('psarticlerequest_categories');

        // adding
        if (Registry::getRequest()->getRequestParameter('all')) {
            $categoriesTable = $this->_getViewName('oxcategories');
            $categoriesToAdd = $this->_getAll($this->_addFilter("select $categoriesTable.oxid " . $this->_getQuery()));
        }

        if (isset($categoriesToAdd) && is_array($categoriesToAdd)) {
            // We force reading from master to prevent issues with slow replications or open transactions (see ESDEV-3804 and ESDEV-3822).
            $database = \OxidEsales\Eshop\Core\DatabaseProvider::getMaster();

            /** @var BaseModel $objectToCategory */
            $objectToCategory = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
            $objectToCategory->init('psarticlerequest_categories');

            foreach ($categoriesToAdd as $sAdd) {
                // check, if it's already in, then don't add it again
                $sSelect = "select 1 from " . $objectToCategoryView . " as psarticlerequest_categories where psarticlerequest_categories.oxcatnid= "
                           . $database->quote($sAdd);

                if ($database->getOne($sSelect)) {
                    continue;
                }

                $objectToCategory->setId(md5($oxId . $sAdd . $shopId));
                $objectToCategory->psarticlerequest_categories__oxcatnid = new \OxidEsales\Eshop\Core\Field($sAdd);
                $objectToCategory->save();
            }

            $this->onCategoriesAdd($categoriesToAdd);
        }
    }


    /**
     * Method used for overloading and embed query.
     *
     * @param string $query
     *
     * @return string
     */
    protected function updateQueryForRemovingArticleFromCategory($query)
    {
        return $query;
    }

    /**
     * Method is used for overloading to do additional actions.
     *
     * @param array  $categoriesToRemove
     * @param string $oxId
     */
    protected function onCategoriesRemoval($categoriesToRemove, $oxId)
    {
    }

    /**
     * Method is used for overloading.
     *
     * @param array $categories
     */
    protected function onCategoriesAdd($categories)
    {
    }

    /**
     * Method is used for overloading to insert additional query condition.
     *
     * @return string
     */
    protected function formQueryToEmbedForUpdatingTime()
    {
        return '';
    }

    /**
     * Method is used for overloading to insert additional query condition.
     *
     * @return string
     */
    protected function formQueryToEmbedForSettingCategoryAsDefault()
    {
        return '';
    }
}



