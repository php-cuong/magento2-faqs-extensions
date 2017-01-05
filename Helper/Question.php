<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-17 17:35:37
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-01-05 09:03:18
 */

namespace PHPCuong\Faq\Helper;

/**
 * Question Helper
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class Question extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \PHPCuong\Faq\Model\ResourceModel\Faq
     */
    protected $_faqResourceModel;

    protected $_faqData;

    /**
     * @var \Magento\User\Model\UserFactory
     */
    protected $_userFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \PHPCuong\Faq\Model\ResourceModel\Faq $faqResourceModel
     * @param \Magento\User\Model\UserFactory $userFactory
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \PHPCuong\Faq\Model\ResourceModel\Faq $faqResourceModel,
        \Magento\User\Model\UserFactory $userFactory
    ) {
        $this->_faqResourceModel = $faqResourceModel;
        $this->_userFactory = $userFactory;
        parent::__construct($context);
    }

    /**
     * Get the full name of author, who created the question
     *
     * @return string|null
     */
    public function getFullName()
    {
        $user_id = ($this->_faqData['user_id'] != null) ? $this->_faqData['user_id'] : '';
        $admin_user = $this->_userFactory->create()->load($user_id);
        if ($admin_user->getUserId()) {
            return trim($admin_user->getFirstname().' '.$admin_user->getLastname());
        }
        return '';
    }

    /**
     * Set value for the $_faqData param
     *
     * @param $faq_id
     * @return $this
     */
    public function getFaq($faq_id)
    {
        $this->_faqData = $this->_faqResourceModel->getFaqStore($faq_id);
        return $this;
    }

    /**
     * Get the ID of Question
     *
     * @return string
     */
    public function getFaqId()
    {
        if (!empty($this->_faqData['faq_id'])) {
            return $this->_faqData['faq_id'];
        }
        return '';
    }

    /**
     * Get the Title of Question
     *
     * @return string
     */
    public function getTitle()
    {
        if (!empty($this->_faqData['title'])) {
            return $this->_faqData['title'];
        }
        return '';
    }

    /**
     * Get the Meta Keywords of Question
     *
     * @return string
     */
    public function getMetaKeywords()
    {
        if (!empty($this->_faqData['meta_keywords'])) {
            return $this->_faqData['meta_keywords'];
        }
        return '';
    }

    /**
     * Get the Meta Description of Question
     *
     * @return string
     */
    public function getMetaDescription()
    {
        if (!empty($this->_faqData['meta_description'])) {
            return $this->_faqData['meta_description'];
        }
        return '';
    }

    /**
     * Get the Content of Question
     *
     * @return string
     */
    public function getContent()
    {
        if (!empty($this->_faqData['content'])) {
            return $this->_faqData['content'];
        }
        return '';
    }

    /**
     * Get the Creation Time of Question
     *
     * @return string
     */
    public function getCreationTime()
    {
        if (!empty($this->_faqData['creation_time'])) {
            $date = new \DateTime($this->_faqData['creation_time']);
            return $date->format('M d, Y H:i:s A');
        }
        return '';
    }

    /**
     * Get the View number of Question
     *
     * @return string
     */
    public function getViewed()
    {
        if (!empty($this->_faqData['viewed'])) {
            return $this->_faqData['viewed'];
        }
        return '';
    }

    /**
     * Get the Category via $faq_id
     *
     * @param $faq_id
     * @return $this
     */
    public function getFaqCategory($faq_id)
    {
        $this->_faqData = $this->_faqResourceModel->getFaqCategory($faq_id);
        return $this;
    }

    /**
     * Get the Identifier of Category
     *
     * @return string
     */
    public function getCategoryIndentifier()
    {
        if (!empty($this->_faqData['identifier'])) {
            return $this->_faqData['identifier'];
        }
        return '';
    }

    /**
     * Get the ID of Category
     *
     * @return string
     */
    public function getCategoryId()
    {
        if (!empty($this->_faqData['category_id'])) {
            return $this->_faqData['category_id'];
        }
        return '';
    }

    /**
     * Get the related question via $faq_id and $category_id
     *
     * @param $faq_id, $category_id
     * @return array|null
     */
    public function getRelatedQuestion($faq_id = null, $category_id = null)
    {
        return $this->_faqResourceModel->getRelatedQuestion($faq_id, $category_id);
    }
}
