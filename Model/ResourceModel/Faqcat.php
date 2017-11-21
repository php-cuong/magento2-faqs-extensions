<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-19 22:03:35
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-11-22 02:52:40
 */

namespace PHPCuong\Faq\Model\ResourceModel;

use Magento\Framework\DB\Select;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPCuong\Faq\Model\Config\Source\Urlkey;
use Magento\Framework\App\Filesystem\DirectoryList;

class Faqcat extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    const FAQ_CATEGORY_ENTITY_TYPE = 'faq-category';

    const FAQ_CATEGORY_FILE_PATH_UPLOADED = 'phpcuong'.DIRECTORY_SEPARATOR.'faq'.DIRECTORY_SEPARATOR.'category'.DIRECTORY_SEPARATOR;

    const FAQ_CATEGORY_FILE_PATH_ACCESS = 'phpcuong/faq/category/';

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
     * Directory List
     *
     * @var DirectoryList
     */
    protected $_directoryList;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param Urlkey $urlKey
     * @param DirectoryList $directoryList
     * @param $connectionName
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Urlkey $urlKey,
        DirectoryList $directoryList
    ) {
        $this->_urlKey       = $urlKey;
        $this->_storeManager = $storeManager;
        $this->_directoryList = $directoryList;
        parent::__construct($context);
    }

    /**
     * Construct
     *
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
     * @return parent
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
        $this->cleanInputs($object);

        $identifier = empty($object->getData('identifier')) ? $object->getTitle() : $object->getData('identifier');

        $object->setIdentifier($this->_urlKey->generateIdentifier($identifier));

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
     * Get the list of Stores
     *
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
     * After save callback
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
     * Save the related datas after the category saved
     *
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
                    'entity_type = ?' => $entity_type,
                ];
                $adapter->delete($this->getTable('url_rewrite'), $url_rewrite_condition);

                $target_path  = Faq::FAQ_CATEGORY_TARGET_PATH.$category_id;
                $request_path = Faq::FAQ_CATEGORY_PATH.'/'.$object->getIdentifier().Faq::FAQ_DOT_HTML;

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

    /**
     * Get the category via $category_id and $storeIds
     *
     * @param $category_id
     * @return array|bool
     */
    public function getFaqCategoryStore($category_id = null)
    {
        if (!$category_id || ($category_id && (int) $category_id <= 0)) {
            return false;
        }

        $storeIds = [
            Store::DEFAULT_STORE_ID,
            (int) $this->_storeManager->getStore()->getId()
        ];

        $select = $this->getConnection()->select()
            ->from(['faqcat' => $this->getMainTable()])
            ->joinLeft(
                ['faqcat_store' => $this->getTable('phpcuong_faq_category_store')],
                'faqcat.category_id = faqcat_store.category_id',
                ['store_id']
            )
            ->where('faqcat.category_id = ?', $category_id)
            ->where('faqcat.is_active = ?', '1')
            ->where('faqcat_store.store_id IN (?)', $storeIds)
            ->group('faqcat.category_id')
            ->limit(1);

        if ($results = $this->getConnection()->fetchRow($select)) {
            return $results;
        }
        return false;
    }

    /**
     * Get the list of Categories via storeIds
     *
     * @return array;
     */
    public function getCategoriesList()
    {
        $adapter = $this->getConnection();

        $storeIds = [
            Store::DEFAULT_STORE_ID,
            (int) $this->_storeManager->getStore()->getId()
        ];

        $select = $adapter->select()
            ->from(['cat' => $this->getTable('phpcuong_faq_category')])
            ->join(['cat_store' => $this->getTable('phpcuong_faq_category_store')], 'cat.category_id = cat_store.category_id', ['store_id'])
            ->where('cat.is_active =?', '1')
            ->where('cat_store.store_id IN (?)', $storeIds)
            ->group('cat.category_id')
            ->order('cat.sort_order ASC');

        return $this->getConnection()->fetchAll($select);
    }

    /**
     * After delete callback
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return parent
     */
    protected function _afterDelete(AbstractModel $object)
    {
        // delete the url rewrite of category
        $adapter = $this->getConnection();
        $condition = [
            'entity_type =?' => Faqcat::FAQ_CATEGORY_ENTITY_TYPE,
            'entity_id =?' => (int) $object->getCategoryId()
        ];
        $adapter->delete($this->getTable('url_rewrite'), $condition);

        // delete the icon file of category
        $image_path = $this->_directoryList->getRoot().DIRECTORY_SEPARATOR.DirectoryList::PUB.DIRECTORY_SEPARATOR.DirectoryList::MEDIA.DIRECTORY_SEPARATOR.$object->getImage();
        if (!empty($object->getImage()) && file_exists($image_path)) {
            unlink($image_path);
        }

        return parent::_afterDelete($object);
    }
}
