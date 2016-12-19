<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-17 17:35:37
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-19 21:17:03
 */
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PHPCuong\Faq\Helper;

use Magento\Framework\App\Action\Action;

/**
 * FAQ Helper
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
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \PHPCuong\Faq\Model\ResourceModel\Faq $faqResourceModel
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \PHPCuong\Faq\Model\ResourceModel\Faq $faqResourceModel
    ) {
        $this->_faqResourceModel     = $faqResourceModel;
        parent::__construct($context);
    }

    /**
     * @return parent
     */
    public function getFaq($faq_id)
    {
        $this->_faqData = $this->_faqResourceModel->getFaqStore($faq_id);
        return $this;
    }

    /**
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
     * @return string
     */
    public function getCreationTime()
    {
        if (!empty($this->_faqData['creation_time'])) {
            return $this->_faqData['creation_time'];
        }
        return '';
    }

    /**
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
     * @param $faq_id
     * @return parent
     */
    public function getFaqCategory($faq_id)
    {
        $this->_faqData = $this->_faqResourceModel->getFaqCategory($faq_id);
        return $this;
    }

    /**
     * @return string
     */
    public function getCategoryIndentifier()
    {
        if (!empty($this->_faqData['identifier'])) {
            return $this->_faqData['identifier'];
        }
        return '';
    }
}
