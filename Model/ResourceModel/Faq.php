<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-16 02:02:38
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-17 03:25:51
 */

namespace PHPCuong\Faq\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\Store;

class Faq extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * construct
     * @return void
     */
    protected function _construct()
    {
        $this->_init('phpcuong_faq', 'faq_id');
    }

    /**
     * Method to run after load
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        $faq_store = $this->getConnection()
            ->select()
            ->from($this->getTable('phpcuong_faq_store'), ['store_id'])
            ->where('faq_id = :faq_id');

        $stores = $this->getConnection()->fetchCol($faq_store, [':faq_id' => $object->getId()]);

        if ($stores) {
            $object->setData('stores', $stores);
        }

        $faq_category = $this->getConnection()
            ->select()
            ->from($this->getTable('phpcuong_faq_category_id'), ['category_id'])
            ->where('faq_id = :faq_id');

        $category = $this->getConnection()->fetchCol($faq_category, [':faq_id' => $object->getId()]);

        if ($category) {
            $object->setData('category_id', $category);
        }

        return parent::_afterLoad($object);
    }
}
