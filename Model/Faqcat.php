<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-19 22:02:46
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-24 20:40:46
 */

namespace PHPCuong\Faq\Model;

class Faqcat extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'phpcuong_faq_category';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('PHPCuong\Faq\Model\ResourceModel\Faqcat');
    }
}
