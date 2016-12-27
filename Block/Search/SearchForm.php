<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-27 15:42:40
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-27 17:38:30
 */

namespace PHPCuong\Faq\Block\Search;

class SearchForm extends \Magento\Framework\View\Element\Template
{
    /**
     * Returns action url for search form
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->_storeManager->getStore()->getUrl('faq/search/', [
        '_secure' => $this->_storeManager->getStore()->isCurrentlySecure()]);
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
}
