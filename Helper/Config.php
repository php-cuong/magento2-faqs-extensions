<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-23 23:54:46
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-24 01:22:57
 */

namespace PHPCuong\Faq\Helper;

use Magento\Store\Model\StoreManagerInterface;
use PHPCuong\Faq\Model\ResourceModel\Faq as FaqResourceModel;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * FAQ Helper
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
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \PHPCuong\Faq\Model\ResourceModel\Faq $faqResourceModel
     * @param \Magento\User\Model\UserFactory $userFactory
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
    }

    public function getFaqCategoryFullPath($identifier)
    {
        return $this->_storeManager->getStore()->getBaseUrl().FaqResourceModel::FAQ_CATEGORY_PATH.'/'.$identifier.FaqResourceModel::FAQ_DOT_HTML;
    }

    public function getFileBaseUrl($path)
    {
        return $this->_storeManager->getStore()->getBaseUrl().DirectoryList::PUB.'/'.DirectoryList::MEDIA.'/'.$path;
    }

    public function getFaqShortDescription($content, $identifier)
    {
        $content = strip_tags($content);
        while (stristr($content, '  '))
        {
            $content = str_replace('  ', ' ', $content);
        }
        $explode = explode(' ', $content);
        if (count($explode) > 50)
        {
            $arg = '';
            for ($i=0; $i<count($explode); $i++) {
                if ($i<=50) {
                    $arg .= $explode[$i].' ';
                }
            }
            if (!empty($arg)) {
                $arg = $arg.'... <a href="'.$this->getFaqFullPath($identifier).'">'.__('Read more').'</a>';
            }
            return $arg;
        }
        return $content;
    }

    public function getFaqFullPath($identifier)
    {
        return $this->_storeManager->getStore()->getBaseUrl().FaqResourceModel::FAQ_QUESTION_PATH.'/'.$identifier.FaqResourceModel::FAQ_DOT_HTML;
    }

    public function getFaqPage()
    {
        return $this->_storeManager->getStore()->getBaseUrl().FaqResourceModel::FAQ_REQUEST_PATH;
    }
}
