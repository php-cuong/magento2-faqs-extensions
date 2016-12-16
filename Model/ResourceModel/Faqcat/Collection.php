<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-16 16:38:14
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-16 16:38:40
 */

namespace PHPCuong\Faq\Model\ResourceModel\Faqcat;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'category_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init('PHPCuong\Faq\Model\Faqcat', 'PHPCuong\Faq\Model\ResourceModel\Faqcat');
    }
}
