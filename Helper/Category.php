<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-20 23:49:42
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-21 01:21:13
 */

namespace PHPCuong\Faq\Helper;

/**
 * FAQ Helper
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class Category extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \PHPCuong\Faq\Model\ResourceModel\Faqcat
     */
    protected $_faqCatResourceModel;

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \PHPCuong\Faq\Model\ResourceModel\Faqcat $faqCatResourceModel
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \PHPCuong\Faq\Model\ResourceModel\Faqcat $faqCatResourceModel,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_faqCatResourceModel     = $faqCatResourceModel;
        $this->_storeManager    = $storeManager;
        parent::__construct($context);
    }

    public function getCategoriesList()
    {
       return $this->_faqCatResourceModel->getCategoriesList();
    }
}
