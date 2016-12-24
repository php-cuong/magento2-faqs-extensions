<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-19 22:06:29
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-24 18:07:24
 */

namespace PHPCuong\Faq\Model\ResourceModel\Faqcat;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'category_id';

    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init('PHPCuong\Faq\Model\Faqcat', 'PHPCuong\Faq\Model\ResourceModel\Faqcat');
    }
}
