<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-23 18:16:21
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-01-06 08:14:21
 */

namespace PHPCuong\Faq\Block\Faq;

use Magento\Framework\View\Element\Template\Context;
use PHPCuong\Faq\Helper\Question as QuestionHelper;
use PHPCuong\Faq\Helper\Category as CategoryHelper;
use PHPCuong\Faq\Model\ResourceModel\Faq as FaqResourceModel;
use Magento\Framework\App\Filesystem\DirectoryList;
use PHPCuong\Faq\Helper\Config as ConfigHelper;

class Faq extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \PHPCuong\Faq\Helper\Question
     */
    protected $_questionHelper;

    /**
     * @var \PHPCuong\Faq\Helper\Category
     */
    protected $_categoryHelper;

    /**
     * @var \PHPCuong\Faq\Model\ResourceModel\Faq
     */
    protected $_faqResourceModel;

    /**
     *
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $_directoryList;

    /**
     * @var array
     */
    protected $_faqCategoriesList = null;

    /**
     * @var \PHPCuong\Faq\Helper\Config
     */
    protected $_configHelper;

    /**
     *
     * @param Context $context
     * @param QuestionHelper $questionHelper
     * @param CategoryHelper $categoryHelper
     * @param DirectoryList $directoryList
     * @param FaqResourceModel $faqResourceModel
     * @param ConfigHelper $configHelper
     */
    public function __construct(
        Context $context,
        QuestionHelper $questionHelper,
        CategoryHelper $categoryHelper,
        DirectoryList $directoryList,
        FaqResourceModel $faqResourceModel,
        ConfigHelper $configHelper
    ) {
        $this->_questionHelper = $questionHelper;
        $this->_categoryHelper = $categoryHelper;
        $this->_directoryList = $directoryList;
        $this->_faqResourceModel = $faqResourceModel;
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

        $this->pageConfig->getTitle()->set(__('FAQ'));

        $this->pageConfig->setKeywords(__('FAQ'));

        $this->pageConfig->setDescription(__('FAQ'));

        $breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs');

        $breadcrumbBlock->addCrumb(
            'home',
            [
                'label' => __('Home'),
                'title' => __('Home'),
                'link' => $this->_storeManager->getStore()->getBaseUrl(),
            ]
        );

        $breadcrumbBlock->addCrumb(
            'faq',
            [
                'label' => __('FAQ'),
                'title' => __('FAQ')
            ]
        );

        return parent::_prepareLayout();
    }

    /**
     * Get the questions most frequently
     *
     * @return array|bool
     */
    public function getFrequentlyAskedQuestion()
    {
        return $this->getFAQ(1);
    }

    /**
     * Get the lastest questions
     *
     * @return array|bool
     */
    public function getLastestFAQ()
    {
        return $this->getFAQ(null, 1);
    }

    /**
     * Get the questions
     *
     * @return array|bool
     */
    public function getFAQ($frequently = null, $lastest = null)
    {
        $select = $this->_faqResourceModel->getConnection()->select()
            ->from(['faq' => $this->_faqResourceModel->getMainTable()])
            ->joinLeft(
                ['faq_store' => $this->_faqResourceModel->getTable('phpcuong_faq_store')],
                'faq.faq_id = faq_store.faq_id',
                ['store_id']
            )
            ->where('faq_store.store_id =?', $this->_storeManager->getStore()->getStoreId())
            ->where('faq.is_active = ?', '1');

        if ($frequently) {
            $select->where('faq.most_frequently = ?', '1');
        }

        $select->group('faq.faq_id');

        if ($frequently) {
            $select->order('faq.sort_order ASC');
        }

        if ($lastest) {
            $select->where('faq.most_frequently <> ?', '1');
            $select->order('faq.faq_id DESC');
        }

        $select->limit(8);

        if ($results = $this->_faqResourceModel->getConnection()->fetchAll($select)) {
            return $results;
        }
        return false;
    }

    /**
     * Get the list of categories
     *
     * @return array|bool
     */
    public function getFaqCategoriesList()
    {
        return $this->_faqCategoriesList;
    }

    /**
     * Get URL of the category
     *
     * @param $identifier
     * @return string
     */
    public function getFaqCategoryFullPath($identifier)
    {
        return $this->_configHelper->getFaqCategoryFullPath($identifier);
    }

    /**
     * Get URL of the files in pub/media folder
     *
     * @param $path
     * @return string
     */
    public function getFileBaseUrl($path)
    {
        return $this->_configHelper->getFileBaseUrl($path);
    }

    /**
     * Get short description of the question
     *
     * @param $content, $identifier
     * @return string
     */
    public function getFaqShortDescription($content, $identifier)
    {
        return $this->_configHelper->getFaqShortDescription($content, $identifier);
    }

    /**
     * Get URL of the question
     *
     * @param $identifier
     * @return string
     */
    public function getFaqFullPath($identifier)
    {
        return $this->_configHelper->getFaqFullPath($identifier);
    }
}
