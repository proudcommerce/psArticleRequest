<?php
/**
 * @package ProudCommerce
 * @author Florian Palme <florian@proudcommerce.com>
 */

namespace ProudCommerce\ArticleRequest\Application\Model;


use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Model\BaseModel;

class ArticleRequest extends BaseModel
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
        $this->init('psarticlerequest');
    }

    /**
     * Inserts object data into DB, returns true on success.
     *
     * @return bool
     */
    protected function _insert()
    {
        // set oxinsert value
        $this->psarticlerequest__oxinsert = new Field(date("Y-m-d H:i:s"));
        return parent::_insert();
    }

    /**
     * Loads psArticleRequest article
     *
     * @return object
     */
    public function getArticle()
    {
        if ($this->_oArticle == null) {
            $this->_oArticle = false;
            /** @var Article $oArticle */
            $oArticle = oxNew(Article::class);
            if ($oArticle->load($this->psarticlerequest__oxartid->value)) {
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
        if ($this->_sTitle == null) {
            $this->_sTitle = false;
            if ($oArticle = $this->getArticle()) {
                $this->_sTitle = $oArticle->oxarticles__oxtitle->value;
                if ($oArticle->oxarticles__oxparentid->value && !$oArticle->oxarticles__oxtitle->value) {
                    /** @var Article $oParent */
                    $oParent = oxNew(Article::class);
                    $oParent->load($oArticle->oxarticles__oxparentid->value);
                    $this->_sTitle = $oParent->oxarticles__oxtitle->value . " " . $oArticle->oxarticles__oxvarselect->value;
                }
            }
        }
        return $this->_sTitle;
    }

    public function getRequestStatus()
    {
        return intval(trim($this->psarticlerequest__oxstatus));
    }
}