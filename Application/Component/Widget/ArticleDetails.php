<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package ProudCommerce\ArticleRequest
 */

namespace ProudCommerce\ArticleRequest\Application\Component\Widget;


use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Registry;

class ArticleDetails extends ArticleDetails_parent
{
    /**
     * @var bool
     */
    protected $_blShowPsArticleRequest;

    protected $_oCaptcha;


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

    /**
     * Check if Turnstile module is available and active
     * @return bool
     */
    protected function isTurnstileAvailable()
    {
        return class_exists('Tabsl\Turnstile\Service\TurnstileService');
    }

    /**
     * Check if we should use Turnstile for this form
     * @return bool
     */
    public function shouldUseTurnstile()
    {
        if (!$this->isTurnstileAvailable()) {
            return false;
        }

        try {
            $turnstileService = new \Tabsl\Turnstile\Service\TurnstileService();
            return method_exists($turnstileService, 'isEnabledForContact') ? $turnstileService->isEnabledForContact() : false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get Turnstile Site Key for template
     * @return string
     */
    public function getTurnstileSiteKey()
    {
        if (!$this->shouldUseTurnstile()) {
            return '';
        }

        try {
            $turnstileService = new \Tabsl\Turnstile\Service\TurnstileService();
            if (method_exists($turnstileService, 'getSiteKey')) {
                return $turnstileService->getSiteKey();
            }
        } catch (\Exception $e) {
            $siteKey = Registry::getConfig()->getConfigParam('sTurnstileSiteKey');
            return $siteKey ?: '';
        }

        return '';
    }

    /**
     * Get captcha
     * @return object|\oeCaptcha|null
     */
    public function getCaptcha()
    {
        if ($this->_oCaptcha === null) {
            if ($this->isTurnstileAvailable()) {
                $this->_oCaptcha = null;
            } else {
                $this->_oCaptcha = oxNew(\oeCaptcha::class);
            }
        }

        return $this->_oCaptcha;
    }
}