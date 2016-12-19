<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-19 23:29:27
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-19 23:29:58
 */

namespace PHPCuong\Faq\Block\Adminhtml\Faqcat\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Internal constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('faqcat_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Category Information'));
    }

    protected function _beforeToHtml()
    {
        $this->setActiveTab('general_section');
        return parent::_beforeToHtml();
    }
}
