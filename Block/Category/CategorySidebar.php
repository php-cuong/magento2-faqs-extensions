<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-20 23:46:21
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-24 18:05:36
 */

namespace PHPCuong\Faq\Block\Category;

use Magento\Framework\View\Element\Template\Context;
use PHPCuong\Faq\Helper\Category as CategoryHelper;
use Magento\Store\Model\StoreManagerInterface;
use PHPCuong\Faq\Model\ResourceModel\Faq;
use PHPCuong\Faq\Helper\Config as ConfigHelper;

class CategorySidebar extends \Magento\Framework\View\Element\Template
{
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     *
     * @var \PHPCuong\Faq\Helper\Category
     */
    protected $_categoryHelper;

    /**
     * @var array
     */
    protected $_faqCategoriesList;

    /**
     * @var \PHPCuong\Faq\Helper\Config
     */
    protected $_configHelper;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param CategoryHelper $categoryHelper
     * @param ConfigHelper $configHelper
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        CategoryHelper $categoryHelper,
        ConfigHelper $configHelper
    ) {
        $this->_storeManager = $storeManager;
        $this->_categoryHelper = $categoryHelper;
        $this->_configHelper = $configHelper;
        parent::__construct($context);
    }

    /**
     *
     * @return parent
     */
    protected function _prepareLayout()
    {
        $this->_faqCategoriesList = $this->_categoryHelper->getCategoriesList();
        return parent::_prepareLayout();
    }

    /**
     * Get List of Categories
     *
     * @return array|null
     */
    public function getFaqCategoriesList()
    {
        return $this->_faqCategoriesList;
    }

    /**
     * Get URL of the category
     *
     * @param $identifier
     * @return array|null
     */
    public function getFaqCategoryFullPath($identifier)
    {
        return $this->_configHelper->getFaqCategoryFullPath($identifier);
    }
}
