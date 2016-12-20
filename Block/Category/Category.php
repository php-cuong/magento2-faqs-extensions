<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-20 23:13:15
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-20 23:43:03
 */

namespace PHPCuong\Faq\Block\Category;

use Magento\Framework\View\Element\Template\Context;
use PHPCuong\Faq\Helper\Question as QuestionHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Page\Config;
use PHPCuong\Faq\Model\ResourceModel\Faq;

class Category extends \Magento\Framework\View\Element\Template
{
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param Context $context
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Config $pageConfig
    ) {
        $this->_storeManager   = $storeManager;
        $this->_pageConfig     = $pageConfig;
        parent::__construct($context);
    }

    /**
     * Add meta information from product to head block
     *
     * @return \PHPCuong\Faq\Block\Question
     */
    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function test()
    {
        return 'Test';
    }
}
