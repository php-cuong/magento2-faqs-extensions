<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-16 02:04:31
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-17 03:24:11
 */

namespace PHPCuong\Faq\Model\ResourceModel\Faq;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'faq_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init('PHPCuong\Faq\Model\Faq', 'PHPCuong\Faq\Model\ResourceModel\Faq');
    }
}
