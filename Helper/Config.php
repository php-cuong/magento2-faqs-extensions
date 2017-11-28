<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-23 23:54:46
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-11-28 17:21:02
 */

namespace PHPCuong\Faq\Helper;

use Magento\Store\Model\StoreManagerInterface;
use PHPCuong\Faq\Model\ResourceModel\Faq as FaqResourceModel;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Config Helper
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class Config
{
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Constructor
     *
     * @param StoreManagerInterface $storeManager
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
    }

    /**
     * Get URL of the category
     *
     * @param $identifier
     * @return string|null
     */
    public function getFaqCategoryFullPath($identifier)
    {
        return $this->_storeManager->getStore()->getBaseUrl().FaqResourceModel::FAQ_CATEGORY_PATH.'/'.$identifier.FaqResourceModel::FAQ_DOT_HTML;
    }

    /**
     * Get URL of the files in pub/media folder
     *
     * @param $path
     * @return string
     */
    public function getFileBaseUrl($path)
    {
        return '/'.DirectoryList::PUB.'/'.DirectoryList::MEDIA.'/'.$path;
    }

    /**
     * Get URL of the category
     *
     * @param $identifier
     * @return string
     */
    public function getFaqFullPath($identifier)
    {
        return $this->_storeManager->getStore()->getBaseUrl().FaqResourceModel::FAQ_QUESTION_PATH.'/'.$identifier.FaqResourceModel::FAQ_DOT_HTML;
    }

    /**
     * Get URL of the FAQ page
     *
     * @return string
     */
    public function getFaqPage()
    {
        return $this->_storeManager->getStore()->getBaseUrl().FaqResourceModel::FAQ_REQUEST_PATH;
    }
}
