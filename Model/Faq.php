<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-16 02:01:39
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-11-11 13:42:36
 */

namespace PHPCuong\Faq\Model;

class Faq extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'phpcuong_faq';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('PHPCuong\Faq\Model\ResourceModel\Faq');
    }
}
