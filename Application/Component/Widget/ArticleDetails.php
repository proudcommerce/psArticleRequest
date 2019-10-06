<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package ProudCommerce\ArticleRequest
 */

namespace ProudCommerce\ArticleRequest\Application\Component\Widget;


use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Core\DatabaseProvider;

class ArticleDetails extends ArticleDetails_parent
{
    /**
     * @var bool
     */
    protected $_blShowPsArticleRequest;


    /**
     * check if request form can be displayed
     *
     * @return bool
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function showPsArticleRequest()
    {
        if ($this->_blShowPsArticleRequest === null) {
            $this->_blShowPsArticleRequest = false;

            $db = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

            // First: check if request is limited to categories
            $categoriesCount = $db->getOne('SELECT COUNT(*) FROM psarticlerequest_categories');

            if ($categoriesCount > 0) {
                /** @var Category $category */
                $category = $this->getActCategory() ?: $this->getProduct()->getCategory();

                if ($category) {
                    $this->_blShowPsArticleRequest = $this->showPsArticleRequestBasedOnCategory($category);
                }
            } else {
                $this->_blShowPsArticleRequest = true;
            }
        }

        return $this->_blShowPsArticleRequest;
    }


    /**
     * checks if request can be showed, bubbeling the tree up
     *
     * @param Category $category
     *
     * @return bool
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    protected function showPsArticleRequestBasedOnCategory(Category $category)
    {
        $db = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
        $count = $db->getOne('SELECT COUNT(*) FROM psarticlerequest_categories WHERE OXCATNID = ' . $db->quote($category->getId()) );

        if ($count <= 0) {
            // Parent Category
            if ($category->getParentCategory()) {
                return $this->showPsArticleRequestBasedOnCategory($category->getParentCategory());
            }

            return false;
        }

        return true;
    }
}