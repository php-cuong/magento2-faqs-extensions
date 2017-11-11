<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-16 02:02:38
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-11-11 14:36:23
 */

namespace PHPCuong\Faq\Model\ResourceModel;

use Magento\Framework\DB\Select;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPCuong\Faq\Model\Config\Source\Urlkey;

class Faq extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
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

    const FAQ_QUESTION_PATH = 'faq';

    const FAQ_CATEGORY_PATH = 'faq/category';

    const FAQ_ENTITY_TYPE = 'faq-question';

    const FAQ_DOT_HTML = '.html';

    const FAQ_QUESTION_TARGET_PATH = 'faq/question/view/faq_id/';

    const FAQ_CATEGORY_TARGET_PATH = 'faq/category/view/category_id/';

    const FAQ_REQUEST_PATH = 'faqs'.Faq::FAQ_DOT_HTML;

    const FAQ_TARGET_PATH = 'faq/faq/view';

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
        $this->_init('phpcuong_faq', 'faq_id');
    }

    /**
     * Method to run after load
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return parent
     */
    protected function _afterLoad(AbstractModel $object)
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
     * Perform operations before object save
     *
     * @param AbstractModel $object
     * @return $this
     * @throws LocalizedException
     */
    protected function _beforeSave(AbstractModel $object)
    {
        $this->cleanInputs($object);

        $identifier = empty($object->getData('identifier')) ? $object->getTitle() : $object->getData('identifier');

        $object->setIdentifier($this->_urlKey->generateIdentifier($identifier));

        if ($this->duplicateFaqIdentifier($object)) {
            throw new LocalizedException(
                __('URL key for specified store already exists.')
            );
        }

        if ($this->isNumericFaqIdentifier($object)) {
            throw new LocalizedException(
                __('The faq URL key cannot be made of only numbers.')
            );
        }
        return $this;
    }

    /**
     * Clean inputs
     *
     * @param AbstractModel $object
     * @return this
     */
    protected function cleanInputs(AbstractModel $object)
    {
        $object->setTitle(trim(strip_tags($object->getTitle())));
        $object->setIdentifier(trim(strip_tags($object->getIdentifier())));
        $object->setMetaKeywords(trim(strip_tags($object->getMetaKeywords())));
        $object->setMetaDescription(trim($object->getMetaDescription()));
        return $this;
    }

    /**
     *  Check whether FAQ identifier is duplicate
     *
     * @param AbstractModel $object
     * @return bool
     */
    protected function duplicateFaqIdentifier(AbstractModel $object)
    {
        $stores = $this->getStores($object);

        $select = $this->getConnection()->select()
            ->from(['faq' => $this->getMainTable()])
            ->join(
                ['faq_store' => $this->getTable('phpcuong_faq_store')],
                'faq.faq_id = faq_store.faq_id',
                ['store_id']
            )
            ->where('faq.identifier = ?', $object->getData('identifier'))
            ->where('faq_store.store_id IN (?)', $stores);

        if ($object->getData('faq_id')) {
            $select->where('faq.faq_id <> ?', $object->getData('faq_id'));
        }

        if ($this->getConnection()->fetchRow($select)) {
            return true;
        }

        return false;
    }

    /**
     *  Check whether Question identifier is numeric
     *
     * @param AbstractModel $object
     * @return bool
     */
    protected function isNumericFaqIdentifier(AbstractModel $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('identifier'));
    }

    /**
     * After save callback
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return parent
     */
    protected function _afterSave(AbstractModel $object)
    {
        $this->saveFaqRelation($object);
        return parent::_afterSave($object);
    }

    /**
     * Get the Question information via $faq_id and $store_id
     *
     * @param $faq_id
     * @return array|bool
     */
    public function getFaqStore($faq_id = null)
    {
        if (!$faq_id || ($faq_id && (int) $faq_id <= 0)) {
            return false;
        }

        $storeIds = [
            Store::DEFAULT_STORE_ID,
            (int) $this->_storeManager->getStore()->getId()
        ];

        $select = $this->getConnection()->select()
            ->from(['faq' => $this->getMainTable()])
            ->joinLeft(
                ['faq_store' => $this->getTable('phpcuong_faq_store')],
                'faq.faq_id = faq_store.faq_id',
                ['store_id']
            )
            ->where('faq.faq_id = ?', $faq_id)
            ->where('faq.is_active = ?', '1')
            ->where('faq_store.store_id IN (?)', $storeIds)
            ->group('faq.faq_id')
            ->limit(1);

        if ($results = $this->getConnection()->fetchRow($select)) {
            return $results;
        }
        return false;
    }

    /**
     * Get the Question information and FAQ Category information via $faq_id and $store_id
     *
     * @param $faq_id
     * @return array|bool
     */
    public function getFaqCategory($faq_id = null)
    {
        $select = $this->getConnection()->select()
            ->from(['faq' => $this->getMainTable()], ['faq_id'])
            ->joinLeft(
                ['faqcat' => $this->getTable('phpcuong_faq_category_id')],
                'faq.faq_id = faqcat.faq_id',
                ['category_id']
            )
            ->joinLeft(
                ['cat' => $this->getTable('phpcuong_faq_category')],
                'faqcat.category_id = cat.category_id',
                ['title', 'identifier']
            )
            ->joinLeft(
                ['cat_store' => $this->getTable('phpcuong_faq_category_store')],
                'faqcat.category_id = cat_store.category_id',
                ['store_id']
            )
            ->where('cat_store.store_id =?', $this->_storeManager->getStore()->getStoreId())
            ->where('faq.faq_id = ?', $faq_id)
            ->where('cat.is_active = ?', '1')
            ->group('faq.faq_id')
            ->limit(1);
        if ($results = $this->getConnection()->fetchRow($select)) {
            return $results;
        }
        return false;
    }

    /**
     * Get the list of related questions
     *
     * @param $faq_id, $category_id
     * @return array|bool
     */
    public function getRelatedQuestion($faq_id = null, $category_id = null)
    {
        $select = $this->getConnection()->select()
            ->from(['faq' => $this->getMainTable()])
            ->joinLeft(
                ['faq_store' => $this->getTable('phpcuong_faq_store')],
                'faq.faq_id = faq_store.faq_id',
                ['store_id']
            )
            ->joinLeft(
                ['faqcat' => $this->getTable('phpcuong_faq_category_id')],
                'faq.faq_id = faqcat.faq_id',
                ['category_id']
            )
            ->where('faq_store.store_id =?', $this->_storeManager->getStore()->getStoreId())
            ->where('faq.faq_id <> ?', $faq_id)
            ->where('faq.is_active = ?', '1')
            ->where('faqcat.category_id = ?', $category_id)
            ->order('faq.sort_order ASC');
        if ($results = $this->getConnection()->fetchAll($select)) {
            return $results;
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
        $flag = 0;
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
     * Update the question number in category
     *
     * @return $this
     */
    public function updateNumberOfFaqsInCategory()
    {
        $adapter = $this->getConnection();

        $select = $adapter->select()->from($this->getTable('phpcuong_faq_category_id'), ['count' => 'COUNT(category_id)', 'category_id'])->group('category_id');

        $faq_category_results = $this->getConnection()->fetchAll($select);

        $adapter->update($this->getTable('phpcuong_faq_category'), ['count' => '0']);

        foreach ($faq_category_results as $value) {
            $adapter->update($this->getTable('phpcuong_faq_category'), ['count' => $value['count']], ['category_id = ?' => (int) $value['category_id']]);
        };

        return $this;
    }

    /**
     * Save the related datas after the question saved
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function saveFaqRelation(AbstractModel $object)
    {
        $category_id = $object->getData('category_id');

        if (is_array($category_id)) {
            $category_id = $category_id[0];
        }

        $faq_id = $object->getData('faq_id');

        $stores = $this->getStores($object);

        if ($faq_id && (int) $faq_id > 0) {
            $adapter = $this->getConnection();

            if ($category_id) {
                $condition = ['faq_id = ?' => (int) $faq_id];
                $adapter->delete($this->getTable('phpcuong_faq_category_id'), $condition);

                $faq_category = [
                    'faq_id' => (int) $faq_id,
                    'category_id' => (int) $category_id
                ];
                $adapter->insertMultiple($this->getTable('phpcuong_faq_category_id'), $faq_category);

                // update the question number in category
                $this->updateNumberOfFaqsInCategory();
            }

            if ($stores) {
                $condition = ['faq_id = ?' => (int) $faq_id];
                $adapter->delete($this->getTable('phpcuong_faq_store'), $condition);

                $entity_type  = Faq::FAQ_ENTITY_TYPE;

                $url_rewrite_condition = [
                    'entity_id = ?' => (int) $faq_id,
                    'entity_type = ?' => $entity_type,
                ];
                $adapter->delete($this->getTable('url_rewrite'), $url_rewrite_condition);

                $target_path  = Faq::FAQ_QUESTION_TARGET_PATH.$faq_id;
                $request_path = Faq::FAQ_QUESTION_PATH.'/'.$object->getIdentifier().Faq::FAQ_DOT_HTML;

                $data = [];
                $url_rewrite_data = [];
                foreach ($stores as $store_id) {
                    $data[] = [
                        'faq_id' => (int) $faq_id,
                        'store_id' => (int) $store_id
                    ];
                    if ($store_id > 0) {
                        $url_rewrite_data[] = [
                            'entity_type'      => $entity_type,
                            'entity_id'        => (int) $faq_id,
                            'request_path'     => $request_path,
                            'target_path'      => $target_path,
                            'is_autogenerated' => 1,
                            'store_id'         => (int) $store_id
                        ];
                    }
                }
                $adapter->insertMultiple($this->getTable('phpcuong_faq_store'), $data);

                $adapter->insertMultiple($this->getTable('url_rewrite'), $url_rewrite_data);
            }
        }
        return $this;
    }

    /**
     * After delete callback
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return parent
     */
    protected function _afterDelete(AbstractModel $object)
    {
        $adapter = $this->getConnection();
        $condition = [
            'entity_type =?' => Faq::FAQ_ENTITY_TYPE,
            'entity_id =?' => (int) $object->getFaqId()
        ];
        $adapter->delete($this->getTable('url_rewrite'), $condition);
        return parent::_afterDelete($object);
    }
}
