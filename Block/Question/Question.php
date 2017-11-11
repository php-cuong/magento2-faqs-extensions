<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-18 15:27:53
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-01-06 08:13:53
 */

namespace PHPCuong\Faq\Block\Question;

use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\Element\Template\Context;
use PHPCuong\Faq\Helper\Question as QuestionHelper;
use PHPCuong\Faq\Model\ResourceModel\Faq;
use PHPCuong\Faq\Helper\Config as ConfigHelper;

class Question extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \PHPCuong\Faq\Helper\Question
     */
    protected $_questionHelper;

    /**
     * @var \PHPCuong\Faq\Helper\Config
     */
    protected $_configHelper;

    /**
     * @var string
     */
    protected $_faqContent = null;

    /**
     * @var string
     */
    protected $_faqTitle = null;

    /**
     * @var string
     */
    protected $_faqCreated = null;

    /**
     * @var string
     */
    protected $_faqViewed = null;

    /**
     * @var string
     */
    protected $_userFullName = null;

    /**
     * @var string
     */
    protected $_faqCategoryTitle = null;

    /**
     * @var array
     */
    protected $_relatedQuestion = null;

    /**
     * @var string
     */
    protected $_faqId = null;

    /**
     *
     * @param Context $context
     * @param QuestionHelper $questionHelper
     * @param ConfigHelper $configHelper
     */
    public function __construct(
        Context $context,
        QuestionHelper $questionHelper,
        ConfigHelper $configHelper
    ) {
        $this->_questionHelper = $questionHelper;
        $this->_configHelper = $configHelper;
        parent::__construct($context);
    }

    /**
     * Get the list of questions
     *
     * @param $faq_id
     * @return array|bool
     */
    protected function getFaq()
    {
        $faq_id = $this->getRequest()->getParam('faq_id');
        return $this->_questionHelper->getFaq($faq_id);
    }

    /**
     * Get the list of categories via faq_id
     *
     * @param $faq_id
     * @return array|bool
     */
    protected function getFaqCategory()
    {
        $faq_id = $this->getRequest()->getParam('faq_id');
        return $this->_questionHelper->getFaqCategory($faq_id);
    }

    /**
     * Return Prepare Layout Parent
     *
     * @return parent
     */
    protected function _prepareLayout()
    {
        $faq = $this->getFaq();

        $this->_faqContent   = $faq->getContent();
        $this->_faqTitle     = $faq->getTitle();
        $this->_faqCreated   = $faq->getCreationTime();
        $this->_faqViewed    = $faq->getViewed();
        $this->_userFullName = $faq->getFullName();
        $this->_faqId        = $faq->getFaqId();

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
                    'link' => $this->_storeManager->getStore()->getBaseUrl() . Faq::FAQ_REQUEST_PATH
                ]
            );
        }

        $faqCategory = $this->getFaqCategory();
        if ($identifier = $faqCategory->getCategoryIndentifier()) {
            $link = $this->_storeManager->getStore()->getBaseUrl().Faq::FAQ_CATEGORY_PATH.'/'.$identifier.Faq::FAQ_DOT_HTML;

            $this->_faqCategoryTitle = '<a href="'.$link.'">'.$faqCategory->getTitle().'</a>';

            $this->_relatedQuestion = $this->_questionHelper->getRelatedQuestion($faq->getFaqId(), $faqCategory->getCategoryId());

            if($breadcrumbsBlock instanceof BlockInterface) {
                $breadcrumbsBlock->addCrumb(
                    'faq.category',
                    [
                        'label' => $faqCategory->getTitle(),
                        'title' => $faqCategory->getTitle(),
                        'link' => $link
                    ]
                );
            }
        }

        if($breadcrumbsBlock instanceof BlockInterface) {
            $breadcrumbsBlock->addCrumb(
                'faq.question.view',
                [
                    'label' => $this->_faqTitle,
                    'title' => $this->_faqTitle
                ]
            );
        }

        $this->pageConfig->getTitle()->set(__('FAQ'));

        $this->pageConfig->getTitle()->prepend($this->_faqTitle);

        $this->pageConfig->setKeywords($faq->getMetaKeywords()? $faq->getMetaKeywords() : $this->_faqTitle);

        $this->pageConfig->setDescription($faq->getMetaDescription()? $faq->getMetaDescription() : $this->_faqTitle);

        return parent::_prepareLayout();
    }

    /**
     * Get the Category Title
     *
     * @return string
     */
    public function getFaqCategoryTitle()
    {
        return $this->_faqCategoryTitle;
    }

    /**
     * Get the full name of author, who created the question
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->_userFullName;
    }

    /**
     * Get the content of question
     *
     * @return string
     */
    public function getFaqContent()
    {
        return $this->_faqContent;
    }

    /**
     * Get the title of question
     *
     * @return string
     */
    public function getFaqTitle()
    {
        return $this->_faqTitle;
    }

    /**
     * Get the creation time of question
     *
     * @return string
     */
    public function getFaqCreated()
    {
        return $this->_faqCreated;
    }

    /**
     * Get the view number of question
     *
     * @return string
     */
    public function getFaqViewed()
    {
        return $this->_faqViewed;
    }

    /**
     * Get the list of related questions
     *
     * @param $faq_id and $category_id
     * @return string
     */
    public function getRelatedQuestion()
    {
        return $this->_relatedQuestion;
    }

    /**
     * Get the question id
     *
     * @return string
     */
    public function getFaqId()
    {
        return $this->_faqId;
    }

    /**
     * Get the URL of question
     *
     * @param $identifier
     * @return string
     */
    public function getFaqFullPath($identifier)
    {
        return $this->_configHelper->getFaqFullPath($identifier);
    }

    /**
     * Get Ajax URL
     *
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->_storeManager->getStore()->getUrl('faq/question/ajax/faq_id/'.$this->getRequest()->getParam('faq_id'), [
        '_secure' => $this->_storeManager->getStore()->isCurrentlySecure()]);
    }
}
