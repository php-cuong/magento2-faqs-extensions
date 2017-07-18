<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-20 23:13:15
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-01-06 08:14:36
 */

namespace PHPCuong\Faq\Block\Category;

use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\Element\Template\Context;
use PHPCuong\Faq\Helper\Category as CategoryHelper;
use PHPCuong\Faq\Helper\Question as QuestionHelper;
use PHPCuong\Faq\Helper\Config as ConfigHelper;
use PHPCuong\Faq\Model\ResourceModel\Faq as FaqResourceModel;
use PHPCuong\Faq\Model\ResourceModel\Faqcat as FaqCatResourceModel;

class Category extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \PHPCuong\Faq\Model\ResourceModel\Faqcat
     */
    protected $_faqCatResourceModel;

    /**
     * @var \PHPCuong\Faq\Model\ResourceModel\Faq
     */
    protected $_faqResourceModel;

    /**
     * @var \PHPCuong\Faq\Helper\Category
     */
    protected $_categoryHelper;

    /**
     * @var array
     */
    protected $_faqCategoryTitle = null;

    /**
     * @var \PHPCuong\Faq\Helper\Config
     */
    protected $_configHelper = null;

    /**
     * @var string
     */
    protected $_faqCategoryIcon = null;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param CategoryHelper $categoryHelper
     * @param Config $pageConfig
     * @param FaqCatResourceModel $faqCatResourceModel
     * @param FaqResourceModel $faqResourceModel
     * @param ConfigHelper $configHelper
     */
    public function __construct(
        Context $context,
        CategoryHelper $categoryHelper,
        FaqCatResourceModel $faqCatResourceModel,
        FaqResourceModel $faqResourceModel,
        ConfigHelper $configHelper
    ) {
        $this->_categoryHelper = $categoryHelper;
        $this->_faqCatResourceModel = $faqCatResourceModel;
        $this->_faqResourceModel = $faqResourceModel;
        $this->_configHelper = $configHelper;
        parent::__construct($context);
    }

    /**
     * Get FAQs Category
     * @param $category_id
     * @return array|null
     */
    protected function getFaqCategory()
    {
        return $this->_faqCatResourceModel->getFaqCategoryStore($this->getRequest()->getParam('category_id'));
    }

    /**
     *
     * @return parent
     */
    protected function _prepareLayout()
    {
        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');

        if($breadcrumbsBlock instanceof BlockInterface) {
            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
            );

            $breadcrumbsBlock->addCrumb(
                'faq',
                [
                    'label' => __('FAQ'),
                    'title' => __('Go to FAQ Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl() . FaqResourceModel::FAQ_REQUEST_PATH
                ]
            );
        }

        $faqCategory = $this->getFaqCategory();

        $this->_faqCategoryTitle = $faqCategory['title'];

        $this->_faqCategoryIcon = $faqCategory['image'];

        if($breadcrumbsBlock instanceof BlockInterface) {
            $breadcrumbsBlock->addCrumb(
                'faq.category',
                [
                    'label' => $faqCategory['title'],
                    'title' => $faqCategory['title']
                ]
            );
        }

        $this->pageConfig->setKeywords($faqCategory['meta_keywords']? $faqCategory['meta_keywords'] : $faqCategory['title']);

        $this->pageConfig->setDescription($faqCategory['meta_description']? $faqCategory['meta_description'] : $faqCategory['title']);

        return parent::_prepareLayout();
    }

    /**
     * Get Category Icon
     *
     * @return string|null
     */
    public function getFaqCategoryIcon()
    {
        return !empty($this->_faqCategoryIcon) ? $this->_configHelper->getFileBaseUrl($this->_faqCategoryIcon) : '';
    }

    /**
     * Get Category Title
     *
     * @return string|null
     */
    public function getFaqCategoryTitle()
    {
        return $this->_faqCategoryTitle;
    }

    /**
     * Get URL of the category
     *
     * @param $identifier
     * @return string|null
     */
    public function getFaqCategoryFullPath($identifier)
    {
        return $this->_configHelper->getFaqCategoryFullPath($identifier);
    }

    /**
     * Get FAQs List
     *
     * @param $category_id
     * @return array|null
     */
    public function getFaqsList()
    {
        return $this->_faqResourceModel->getRelatedQuestion(null, (int) $this->getRequest()->getParam('category_id'));
    }

    /**
     * Get URL of the question
     *
     * @param $identifier
     * @return string|null
     */
    public function getFaqFullPath($identifier)
    {
        return $this->_configHelper->getFaqFullPath($identifier);
    }

    /**
     * Get Short Description of the question
     *
     * @param $content, $identifier
     * @return string|null
     */
    public function getFaqShortDescription($content, $identifier)
    {
        return $this->_configHelper->getFaqShortDescription($content, $identifier);
    }
}
