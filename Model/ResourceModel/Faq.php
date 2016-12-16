<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-16 02:02:38
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-17 06:28:28
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

    /**
     * after save callback
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return parent
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->saveFaqRelation($object);
        return parent::_afterSave($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function saveFaqRelation(\Magento\Framework\Model\AbstractModel $object)
    {
        $category_id = $object->getData('category_id');
        $faq_id = $object->getData('faq_id');
        $stores = $object->getData('stores');

        if ($faq_id && (int) $faq_id > 0) {

            $adapter = $this->getConnection();

            $where = ['faq_id = ?' => (int) $faq_id];
            $bind = ['category_id' => (int)$category_id];
            $adapter->update($this->getTable('phpcuong_faq_category_id'), $bind, $where);

            $condition = ['faq_id = ?' => (int) $faq_id];
            $adapter->delete($this->getTable('phpcuong_faq_store'), $condition);

            $data = [];
            foreach ($stores as $store_id) {
                $data[] = [
                    'faq_id' => (int) $faq_id,
                    'store_id' => (int) $store_id
                ];
            }
            $adapter->insertMultiple($this->getTable('phpcuong_faq_store'), $data);
        }
        return $this;
    }
}
