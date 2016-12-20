<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-18 15:27:53
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-21 03:01:42
 */

namespace PHPCuong\Faq\Block\Question;

use Magento\Framework\View\Element\Template\Context;
use PHPCuong\Faq\Helper\Question as QuestionHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Page\Config;
use PHPCuong\Faq\Model\ResourceModel\Faq;

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
    protected $_pageConfig = null;

    protected $_faqContent = null;

    protected $_faqTitle = null;

    protected $_faqCreated = null;

    protected $_faqViewed = null;

    protected $_userFullName = null;

    protected $_faqCategoryTitle = null;

    protected $_relatedQuestion = null;

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

        $this->_faqContent   = $faq->getContent();
        $this->_faqTitle     = $faq->getTitle();
        $this->_faqCreated   = $faq->getCreationTime();
        $this->_faqViewed    = $faq->getViewed();
        $this->_userFullName = $faq->getFullName();

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

            $link = $this->_storeManager->getStore()->getBaseUrl().Faq::FAQ_CATEGORY_PATH.'/'.$identifier.Faq::FAQ_DOT_HTML;

            $this->_faqCategoryTitle = '<a href="'.$link.'">'.$faqCategory->getTitle().'</a>';

            $this->_relatedQuestion = $this->_questionHelper->getRelatedQuestion($faq->getFaqId(), $faqCategory->getCategoryId());

            $breadcrumbsBlock->addCrumb(
                'faq.category',
                [
                    'label' => $faqCategory->getTitle(),
                    'title' => $faqCategory->getTitle(),
                    'link'  => $link
                ]
            );
        }

        $breadcrumbsBlock->addCrumb(
            'faq.question.view',
            [
                'label' => $this->_faqTitle,
                'title' => $this->_faqTitle
            ]
        );

        $this->_pageConfig->getTitle()->set($this->_faqTitle);

        $this->_pageConfig->setKeywords($faq->getMetaKeywords()? $faq->getMetaKeywords() : $this->_faqTitle);

        $this->_pageConfig->setDescription($faq->getMetaDescription()? $faq->getMetaDescription() : $this->_faqTitle);

        return parent::_prepareLayout();
    }

    public function getFaqCategoryTitle()
    {
        return $this->_faqCategoryTitle;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->_userFullName;
    }

    /**
     * @return string
     */
    public function getFaqContent()
    {
        return $this->_faqContent;
    }

    /**
     * @return string
     */
    public function getFaqTitle()
    {
        return $this->_faqTitle;
    }

    /**
     * @return string
     */
    public function getFaqCreated()
    {
        return $this->_faqCreated;
    }

    /**
     * @return string
     */
    public function getFaqViewed()
    {
        return $this->_faqViewed;
    }

    public function getRelatedQuestion()
    {
        return $this->_relatedQuestion;
    }

    public function getFaqPath()
    {
        return Faq::FAQ_QUESTION_PATH;
    }

    public function getFaqDotHtml()
    {
        return Faq::FAQ_DOT_HTML;
    }

    public function getBaseUrlStore()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }
}
