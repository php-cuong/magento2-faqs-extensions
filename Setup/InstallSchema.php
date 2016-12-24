<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-16 00:11:08
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-24 20:44:55
 */

namespace PHPCuong\Faq\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'phpcuong_faq'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('phpcuong_faq')
        )->addColumn(
            'faq_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'FAQ ID'
        )->addColumn(
            'title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'FAQ Title'
        )->addColumn(
            'identifier',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'FAQ String Identifier'
        )->addColumn(
            'content',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '4g',
            ['nullable' => true],
            'FAQ Content'
        )->addColumn(
            'creation_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'FAQ Creation Time'
        )->addColumn(
            'update_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'FAQ Modification Time'
        )->addColumn(
            'is_active',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Is FAQ Active'
        )->addColumn(
            'sort_order',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '0'],
            'FAQ Sort Order'
        )->addColumn(
            'liked',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '0'],
            'The number of liked'
        )->addColumn(
            'disliked',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '0'],
            'The number of disliked'
        )->addColumn(
            'viewed',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => '0'],
            'The number of viewed'
        )->addColumn(
            'most_frequently',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '0'],
            'FAQ Most Frequently'
        )->addColumn(
            'user_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => '1'],
            'Admin User ID'
        )->addColumn(
            'meta_keywords',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            ['nullable' => true],
            'FAQ Meta Keywords'
        )->addColumn(
            'meta_description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            ['nullable' => true],
            'FAQ Meta Description'
        )->addIndex(
            $setup->getIdxName(
                $installer->getTable('phpcuong_faq'),
                ['title', 'identifier', 'content'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['title', 'identifier', 'content'],
            ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
            'FAQ Table'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'phpcuong_faq_store'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('phpcuong_faq_store')
        )->addColumn(
            'faq_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false],
            'FAQ ID'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Store ID'
        )->addIndex(
            $installer->getIdxName('phpcuong_faq_store', ['store_id']),
            ['store_id']
        )->addForeignKey(
            $installer->getFkName('phpcuong_faq_store', 'faq_id', 'phpcuong_faq', 'faq_id'),
            'faq_id',
            $installer->getTable('phpcuong_faq'),
            'faq_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('phpcuong_faq_store', 'store_id', 'store', 'store_id'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'FAQ To Store Linkage Table'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'phpcuong_faq_category'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('phpcuong_faq_category')
        )->addColumn(
            'category_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Category ID'
        )->addColumn(
            'title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Category Title'
        )->addColumn(
            'meta_keywords',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            ['nullable' => true],
            'Category Meta Keywords'
        )->addColumn(
            'meta_description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            ['nullable' => true],
            'Category Meta Description'
        )->addColumn(
            'image',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            ['nullable' => true],
            'Image of Category'
        )->addColumn(
            'identifier',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            100,
            ['nullable' => true, 'default' => null],
            'Category String Identifier'
        )->addColumn(
            'user_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => '1'],
            'Admin User ID'
        )->addColumn(
            'count',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => '0'],
            'Number of FAQs in Category'
        )->addColumn(
            'is_active',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Is Category Active'
        )->addColumn(
            'sort_order',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '0'],
            'Category Sort Order'
        )->addColumn(
            'creation_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Category Creation Time'
        )->addColumn(
            'update_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'Category Modification Time'
        )->addIndex(
            $setup->getIdxName(
                $installer->getTable('phpcuong_faq_category'),
                ['title', 'meta_keywords', 'meta_description', 'identifier'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['title', 'meta_keywords', 'meta_description', 'identifier'],
            ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
            'FAQ Category Table'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'phpcuong_faq_category_store'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('phpcuong_faq_category_store')
        )->addColumn(
            'category_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false],
            'Category ID'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Store ID'
        )->addIndex(
            $installer->getIdxName('phpcuong_faq_category_store', ['store_id']),
            ['store_id']
        )->addForeignKey(
            $installer->getFkName('phpcuong_faq_category_store', 'category_id', 'phpcuong_faq_category', 'category_id'),
            'category_id',
            $installer->getTable('phpcuong_faq_category'),
            'category_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('phpcuong_faq_category_store', 'store_id', 'store', 'store_id'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'FAQ Category To Store Linkage Table'
        );
        $installer->getConnection()->createTable($table);

        $installer->endSetup();

        /**
         * Create table 'phpcuong_faq_category_id'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('phpcuong_faq_category_id')
        )->addColumn(
            'faq_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'FAQ ID'
        )->addColumn(
            'category_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false],
            'Category ID'
        )->addForeignKey(
            $installer->getFkName('phpcuong_faq_category_id', 'category_id', 'phpcuong_faq_category', 'category_id'),
            'category_id',
            $installer->getTable('phpcuong_faq_category'),
            'category_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('phpcuong_faq_category_id', 'faq_id', 'phpcuong_faq', 'faq_id'),
            'faq_id',
            $installer->getTable('phpcuong_faq'),
            'faq_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'FAQ and FAQ Category To Store Linkage Table'
        );
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
