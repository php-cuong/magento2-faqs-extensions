<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-19 22:03:35
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-20 02:39:11
 */

namespace PHPCuong\Faq\Model\ResourceModel;

use Magento\Framework\DB\Select;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPCuong\Faq\Model\Config\Source\Urlkey;

class Faqcat extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    const FAQ_CATEGORY_ENTITY_TYPE = 'faq-category';
    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * Url key
     *
     * @var Urlkey
     */
    protected $_urlKey;
    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Urlkey $urlKey,
        $connectionName = null
    ) {
        $this->_urlKey       = $urlKey;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $connectionName);
    }
    /**
     * construct
     * @return void
     */
    protected function _construct()
    {
        $this->_init('phpcuong_faq_category', 'category_id');
    }

    /**
     * Method to run after load
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterLoad(AbstractModel $object)
    {
        $category_store = $this->getConnection()
            ->select()
            ->from($this->getTable('phpcuong_faq_category_store'), ['store_id'])
            ->where('category_id = :category_id');

        $stores = $this->getConnection()->fetchCol($category_store, [':category_id' => $object->getId()]);

        if ($stores) {
            $object->setData('stores', $stores);
        }

        return parent::_afterLoad($object);
    }

    /**
     * Perform operations before object save
     *
     * @param AbstractModel $object
     * @return $this
     * @throws LocalizedException
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if (empty($object->getData('identifier'))) {
            $identifier = $this->_urlKey->generateIdentifier($object->getTitle());
            $object->setIdentifier($identifier);
        }

        if ($this->duplicateCategoryIdentifier($object)) {
            throw new LocalizedException(
                __('URL key for specified store already exists.')
            );
        }

        if ($this->isNumericCategoryIdentifier($object)) {
            throw new LocalizedException(
                __('The Category URL key cannot be made of only numbers.')
            );
        }
    }

    /**
     *  Check whether Category identifier is numeric
     *
     * @param AbstractModel $object
     * @return bool
     */
    protected function isNumericCategoryIdentifier(AbstractModel $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('identifier'));
    }

    /**
     *  Check whether FAQ identifier is duplicate
     *
     * @param AbstractModel $object
     * @return bool
     */
    protected function duplicateCategoryIdentifier(AbstractModel $object)
    {
        $stores = $this->getStores($object);

        $select = $this->getConnection()->select()
            ->from(['cat' => $this->getMainTable()])
            ->join(
                ['cat_store' => $this->getTable('phpcuong_faq_category_store')],
                'cat.category_id = cat_store.category_id',
                ['store_id']
            )
            ->where('cat.identifier = ?', $object->getData('identifier'))
            ->where('cat_store.store_id IN (?)', $stores);

        if ($object->getData('category_id')) {
            $select->where('cat.category_id <> ?', $object->getData('category_id'));
        }

        if ($this->getConnection()->fetchRow($select)) {
            return true;
        }

        return false;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return array
     */
    protected function getStores(AbstractModel $object)
    {
        if ($this->_storeManager->hasSingleStore()) {
            $stores = [Store::DEFAULT_STORE_ID];
        } else {
            $stores = (array)$object->getData('stores');
        }
        $rs = [];
        foreach ($stores as $store) {
            if ($store == Store::DEFAULT_STORE_ID) {
                $_stores = $this->_storeManager->getStores(true, true);
                foreach ($_stores as $value) {
                    if ($value->getData()['is_active']) {
                        $rs[] = $value->getData()['store_id'];
                    }
                }
                break;
            }
        }
        $stores   = array_unique(array_merge($stores, $rs));
        return $stores;
    }

    /**
     * after save callback
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return parent
     */
    protected function _afterSave(AbstractModel $object)
    {
        $this->saveCategoryRelation($object);
        return parent::_afterSave($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function saveCategoryRelation(AbstractModel $object)
    {
        $category_id = $object->getData('category_id');

        $stores = $this->getStores($object);

        if ($category_id && (int) $category_id > 0) {

            $adapter = $this->getConnection();

            if ($stores) {
                $condition = ['category_id = ?' => (int) $category_id];
                $adapter->delete($this->getTable('phpcuong_faq_category_store'), $condition);

                $entity_type  = Faqcat::FAQ_CATEGORY_ENTITY_TYPE;

                $url_rewrite_condition = [
                    'entity_id = ?' => (int) $category_id,
                    'entity_type = ?' => (int) $entity_type,
                ];
                $adapter->delete($this->getTable('url_rewrite'), $url_rewrite_condition);

                $target_path  = 'faq/category/view/category_id/'.$category_id;
                $request_path = Faq::FAQ_CATEGORY_PATH.'/'.$object->getIdentifier().'.html';

                $data = [];
                $url_rewrite_data = [];
                foreach ($stores as $store_id) {
                    $data[] = [
                        'category_id' => (int) $category_id,
                        'store_id' => (int) $store_id
                    ];
                    if ($store_id > 0) {
                        $url_rewrite_data[] = [
                            'entity_type'      => $entity_type,
                            'entity_id'        => (int) $category_id,
                            'request_path'     => $request_path,
                            'target_path'      => $target_path,
                            'is_autogenerated' => 1,
                            'store_id'         => (int) $store_id
                        ];
                    }
                }
                $adapter->insertMultiple($this->getTable('phpcuong_faq_category_store'), $data);

                $adapter->insertMultiple($this->getTable('url_rewrite'), $url_rewrite_data);
            }
        }
        return $this;
    }
}
