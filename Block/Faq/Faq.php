<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-23 18:16:21
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-24 00:08:10
 */
namespace PHPCuong\Faq\Block\Faq;

use Magento\Framework\View\Element\Template\Context;
use PHPCuong\Faq\Helper\Question as QuestionHelper;
use PHPCuong\Faq\Helper\Category as CategoryHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Page\Config;
use PHPCuong\Faq\Model\ResourceModel\Faq as FaqResourceModel;
use Magento\Framework\App\Filesystem\DirectoryList;


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
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     *
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $_directoryList;

    /**
     * @var \Magento\Framework\View\Page\Config
     */
    protected $_pageConfig;

    protected $_faqCategoriesList = null;

    protected $_configHelper = null;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        QuestionHelper $questionHelper,
        CategoryHelper $categoryHelper,
        DirectoryList $directoryList,
        Config $pageConfig,
        FaqResourceModel $faqResourceModel,
        \PHPCuong\Faq\Helper\Config $configHelper
    ) {
        $this->_questionHelper = $questionHelper;
        $this->_storeManager   = $storeManager;
        $this->_pageConfig     = $pageConfig;
        $this->_categoryHelper = $categoryHelper;
        $this->_directoryList = $directoryList;
        $this->_faqResourceModel = $faqResourceModel;
        $this->_configHelper = $configHelper;
        parent::__construct($context);
    }

    /**
     * Add meta information from product to head block
     *
     * @return \PHPCuong\Faq\Block\Faq
     */
    protected function _prepareLayout()
    {
        $this->_faqCategoriesList = $this->_categoryHelper->getCategoriesList();

        $this->_pageConfig->getTitle()->set(__('FAQ'));

        $this->_pageConfig->setKeywords(__('FAQ'));

        $this->_pageConfig->setDescription(__('FAQ'));

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

    public function getFrequentlyAskedQuestion()
    {
        return $this->getFAQ(1);
    }

    public function getLastestFAQ()
    {
        return $this->getFAQ(null, 1);
    }

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

    public function getFaqCategoriesList()
    {
        return $this->_faqCategoriesList;
    }

    public function getFaqCategoryFullPath($identifier)
    {
        return $this->_configHelper->getFaqCategoryFullPath($identifier);
    }

    public function getFileBaseUrl($path)
    {
        return $this->_configHelper->getFileBaseUrl($path);
    }

    public function getFaqShortDescription($content, $identifier)
    {
        return $this->_configHelper->getFaqShortDescription($content, $identifier);
    }

    public function getFaqFullPath($identifier)
    {
        return $this->_configHelper->getFaqFullPath($identifier);
    }
}
