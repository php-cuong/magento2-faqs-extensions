<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-19 23:20:49
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-19 23:21:27
 */

namespace PHPCuong\Faq\Block\Adminhtml;

/**
 * Adminhtml cms blocks content block
 */
class Faqcat extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'PHPCuong_Faqcat';
        $this->_controller = 'Adminhtml_Faqcat';
        $this->_headerText = __('FAQ Categories Manager');
        $this->_addButtonLabel = __('Add New Category');
        parent::_construct();
    }
}
