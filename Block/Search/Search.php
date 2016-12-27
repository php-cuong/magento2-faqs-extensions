<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-27 16:35:58
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-27 22:09:45
 */

namespace PHPCuong\Faq\Block\Search;

use Magento\Framework\View\Element\Template\Context;
use PHPCuong\Faq\Helper\Question as QuestionHelper;
use PHPCuong\Faq\Helper\Category as CategoryHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Page\Config;
use PHPCuong\Faq\Model\ResourceModel\Faq as FaqResourceModel;
use Magento\Framework\App\Filesystem\DirectoryList;
use PHPCuong\Faq\Helper\Config as ConfigHelper;

class Search extends \Magento\Framework\View\Element\Template
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
     * @param StoreManagerInterface $storeManager
     * @param QuestionHelper $questionHelper
     * @param CategoryHelper $categoryHelper
     * @param DirectoryList $directoryList
     * @param Config $pageConfig
     * @param FaqResourceModel $faqResourceModel
     * @param ConfigHelper $configHelper
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        QuestionHelper $questionHelper,
        CategoryHelper $categoryHelper,
        DirectoryList $directoryList,
        Config $pageConfig,
        FaqResourceModel $faqResourceModel,
        ConfigHelper $configHelper
    ) {
        $this->_questionHelper = $questionHelper;
        $this->_storeManager = $storeManager;
        $this->_pageConfig = $pageConfig;
        $this->_categoryHelper = $categoryHelper;
        $this->_directoryList = $directoryList;
        $this->_faqResourceModel = $faqResourceModel;
        $this->_configHelper = $configHelper;
        parent::__construct($context);
    }

    /**
     * Get Text search from url
     *
     * @return string
     */
    public function getTextSearch()
    {
        return ($this->getRequest()->getParam('s')) ? $this->getRequest()->getParam('s') : '';
    }

    /**
     *
     * @return parent
     */
    protected function _prepareLayout()
    {

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
                'title' => __('Go to FAQ Page'),
                'link'  => $this->_storeManager->getStore()->getBaseUrl().FaqResourceModel::FAQ_REQUEST_PATH
            ]
        );

        $breadcrumbBlock->addCrumb(
            'search',
            [
                'label' => __('Search: ').$this->getTextSearch(),
                'title' => __('Search: ').$this->getTextSearch()
            ]
        );

        $this->_pageConfig->getTitle()->set(__('FAQ'));

        $this->_pageConfig->getTitle()->prepend(__('Search: ').$this->getTextSearch());

        $this->_pageConfig->setKeywords(__('Search: ').$this->getTextSearch());

        $this->_pageConfig->setDescription(__('Search: ').$this->getTextSearch());

        return parent::_prepareLayout();
    }

    /**
     * Get FAQs via text search
     *
     * @return array|bool
     */
    public function getFaqsList()
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

        $textSearch = $this->clearTextSearch();
        $explode = explode(' ', $textSearch);

        for ($i=0; $i<count($explode); $i++) {
            if ($i == 0) {
                $select->where('faq.title LIKE ?', "%{$explode[$i]}%");
            } else {
                $select->orWhere('faq.title LIKE ?', "%{$explode[$i]}%");
            }
            $select->orWhere('faq.content LIKE ?', "%{$explode[$i]}%");
        }

        $select->group('faq.faq_id');
        $select->order('faq.faq_id DESC');

        if ($results = $this->_faqResourceModel->getConnection()->fetchAll($select)) {
            return $results;
        }

        return false;
    }

    /**
     * Return the letters a-zA-z0-9
     *
     * @return string
     */
    protected function clearTextSearch()
    {
        $textSearch = strtolower($this->getTextSearch());

        while (stristr($textSearch, '-')) {
            $textSearch = str_replace('-', ' ', $textSearch);
        }

        while (stristr($textSearch, '  ')) {
            $textSearch = str_replace('  ', ' ', $textSearch);
        }

        $filter = new \Zend\I18n\Filter\Alnum(true);

        return $filter->filter($textSearch);
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
