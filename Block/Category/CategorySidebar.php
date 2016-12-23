<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-20 23:46:21
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-24 00:13:03
 */

namespace PHPCuong\Faq\Block\Category;

use Magento\Framework\View\Element\Template\Context;
use PHPCuong\Faq\Helper\Category as CategoryHelper;
use Magento\Store\Model\StoreManagerInterface;
use PHPCuong\Faq\Model\ResourceModel\Faq;

class CategorySidebar extends \Magento\Framework\View\Element\Template
{
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Category Helper
     *
     * @var \PHPCuong\Faq\Helper\Category
     */
    protected $_categoryHelper;

    protected $_faqCategoriesList;

    protected $_configHelper;

    /**
     * @param Context $context
     * @param CategoryHelper $categoryHelper
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        CategoryHelper $categoryHelper,
        \PHPCuong\Faq\Helper\Config $configHelper
    ) {
        $this->_storeManager   = $storeManager;
        $this->_categoryHelper = $categoryHelper;
        $this->_configHelper = $configHelper;
        parent::__construct($context);
    }

    /**
     * Add meta information from product to head block
     *
     * @return \PHPCuong\Faq\Block\Question
     */
    protected function _prepareLayout()
    {
        $this->_faqCategoriesList = $this->_categoryHelper->getCategoriesList();
        return parent::_prepareLayout();
    }

    public function getFaqCategoriesList()
    {
        return $this->_faqCategoriesList;
    }

    public function getFaqCategoryFullPath($identifier)
    {
        return $this->_configHelper->getFaqCategoryFullPath($identifier);
    }
}
