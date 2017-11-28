<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-20 23:13:15
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-11-28 19:25:54
 */

namespace PHPCuong\Faq\Block\Category;

use Magento\Framework\View\Element\Template\Context;
use PHPCuong\Faq\Helper\Category as CategoryHelper;
use PHPCuong\Faq\Helper\Question as QuestionHelper;
use PHPCuong\Faq\Helper\Config as ConfigHelper;
use PHPCuong\Faq\Model\ResourceModel\Faq as FaqResourceModel;
use PHPCuong\Faq\Model\ResourceModel\Faqcat as FaqCatResourceModel;
use Magento\Cms\Model\Template\FilterProvider;

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
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $filterProvider;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param CategoryHelper $categoryHelper
     * @param Config $pageConfig
     * @param FaqCatResourceModel $faqCatResourceModel
     * @param FaqResourceModel $faqResourceModel
     * @param ConfigHelper $configHelper
     * @param FilterProvider $filterProvider
     */
    public function __construct(
        Context $context,
        CategoryHelper $categoryHelper,
        FaqCatResourceModel $faqCatResourceModel,
        FaqResourceModel $faqResourceModel,
        ConfigHelper $configHelper,
        FilterProvider $filterProvider
    ) {
        $this->_categoryHelper = $categoryHelper;
        $this->_faqCatResourceModel = $faqCatResourceModel;
        $this->_faqResourceModel = $faqResourceModel;
        $this->_configHelper = $configHelper;
        $this->filterProvider = $filterProvider;
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

        $breadcrumbsBlock->addCrumb(
            'home',
            [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link'  => $this->_storeManager->getStore()->getBaseUrl()
            ]
        );

        $breadcrumbsBlock->addCrumb(
            'faq',
            [
                'label' => __('FAQs'),
                'title' => __('Go to FAQs Page'),
                'link'  => $this->_storeManager->getStore()->getBaseUrl().FaqResourceModel::FAQ_REQUEST_PATH
            ]
        );

        $faqCategory = $this->getFaqCategory();

        $this->_faqCategoryTitle = __($faqCategory['title']);

        $this->_faqCategoryIcon = $faqCategory['image'];

        $breadcrumbsBlock->addCrumb(
            'faq.category',
            [
                'label' => __($faqCategory['title']),
                'title' => __($faqCategory['title'])
            ]
        );

        $this->pageConfig->setKeywords($faqCategory['meta_keywords']? __($faqCategory['meta_keywords']) : $this->_faqCategoryTitle);

        $this->pageConfig->setDescription($faqCategory['meta_description']? __($faqCategory['meta_description']) : $this->_faqCategoryTitle);

        return parent::_prepareLayout();
    }

    /**
     * Filter provider
     *
     * @param string $content
     * @return string
     */
    public function filterProvider($content)
    {
        return $this->filterProvider->getBlockFilter()
            ->setStoreId($this->_storeManager->getStore()->getId())
            ->filter($content);
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
