<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-18 15:27:53
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-19 02:40:45
 */

namespace PHPCuong\Faq\Block;

use Magento\Framework\View\Element\Template\Context;
use PHPCuong\Faq\Helper\Question as QuestionHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Page\Config;

class Question extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \PHPCuong\Faq\Helper\Question
     */
    protected $_questionHelper;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\View\Page\Config
     */
    protected $_pageConfig;

    /**
     * @param Context $context
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        QuestionHelper $questionHelper,
        Config $pageConfig
    ) {
        $this->_questionHelper = $questionHelper;
        $this->_storeManager   = $storeManager;
        $this->_pageConfig     = $pageConfig;
        parent::__construct($context);
    }

    protected function getFaq()
    {
        $faq_id = $this->getRequest()->getParam('faq_id');
        return $this->_questionHelper->getFaq($faq_id);
    }

    protected function getFaqCategory()
    {
        $faq_id = $this->getRequest()->getParam('faq_id');
        return $this->_questionHelper->getFaqCategory($faq_id);
    }

    /**
     * Add meta information from product to head block
     *
     * @return \PHPCuong\Faq\Block\Question
     */
    protected function _prepareLayout()
    {
        $faq = $this->getFaq();
        $faqTitle = $faq->getTitle();
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
                'label' => __('FAQ'),
                'title' => __('Go to FAQ Page'),
                'link'  => $this->_storeManager->getStore()->getBaseUrl().'faqs.html'
            ]
        );

        $faqCategory = $this->getFaqCategory();
        if ($identifier = $faqCategory->getCategoryIndentifier()) {
            $breadcrumbsBlock->addCrumb(
                'faq.category',
                [
                    'label' => $faqCategory->getTitle(),
                    'title' => $faqCategory->getTitle(),
                    'link'  => $this->_storeManager->getStore()->getBaseUrl().'faq/category/'.$identifier.'.html'
                ]
            );
        }

        $breadcrumbsBlock->addCrumb(
            'faq.question.view',
            [
                'label' => $faqTitle,
                'title' => $faqTitle
            ]
        );

        $this->_pageConfig->getTitle()->set($faqTitle);

        $this->_pageConfig->setKeywords($faq->getMetaKeywords()? $faq->getMetaKeywords() : $faqTitle);

        $this->_pageConfig->setDescription($faq->getMetaDescription()? $faq->getMetaDescription() : $faqTitle);

        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getFaqContent()
    {
        return $this->getFaq()->getContent();
    }

    /**
     * @return string
     */
    public function getFaqTitle()
    {
        return $this->getFaq()->getTitle();
    }
}
