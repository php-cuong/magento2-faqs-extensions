<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-20 23:46:21
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-01-06 07:59:25
 */

namespace PHPCuong\Faq\Block\Category;

use Magento\Framework\View\Element\Template\Context;
use PHPCuong\Faq\Helper\Category as CategoryHelper;
use PHPCuong\Faq\Model\ResourceModel\Faq;
use PHPCuong\Faq\Helper\Config as ConfigHelper;

class CategorySidebar extends \Magento\Framework\View\Element\Template
{
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
     * @param CategoryHelper $categoryHelper
     * @param ConfigHelper $configHelper
     */
    public function __construct(
        Context $context,
        CategoryHelper $categoryHelper,
        ConfigHelper $configHelper
    ) {
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
