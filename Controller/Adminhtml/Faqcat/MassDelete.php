<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-20 02:17:36
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-24 17:11:14
 */

namespace PHPCuong\Faq\Controller\Adminhtml\Faqcat;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use PHPCuong\Faq\Model\ResourceModel\Faqcat\CollectionFactory;
use PHPCuong\Faq\Model\ResourceModel\Faqcat;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PHPCuong_Faq::category_delete';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $page) {

            $page->delete();

            $category_id = $page->getData()['category_id'];

            $url_rewrite_model = $this->_objectManager->create('Magento\UrlRewrite\Model\UrlRewrite');

            $urls_rewrite = $url_rewrite_model->getCollection()
            ->addFieldToFilter('entity_type', Faqcat::FAQ_CATEGORY_ENTITY_TYPE)
            ->addFieldToFilter('entity_id', $category_id)
            ->load()->getData();
            foreach ($urls_rewrite as $value) {
                $url_rewrite_model = $this->_objectManager->create('Magento\UrlRewrite\Model\UrlRewrite');
                $url_rewrite_model->load($value['url_rewrite_id'])->delete();
            }
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
