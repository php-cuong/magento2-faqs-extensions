<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-16 17:41:57
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-17 04:38:20
 */

namespace PHPCuong\Faq\Block\Adminhtml;

/**
 * Adminhtml cms blocks content block
 */
class Faq extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'PHPCuong_Faq';
        $this->_controller = 'Adminhtml_Faq';
        $this->_headerText = __('FAQs Manager');
        $this->_addButtonLabel = __('Add New FAQ');
        parent::_construct();
    }
}
