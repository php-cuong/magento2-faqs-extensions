<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-19 23:12:25
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-24 17:10:18
 */

namespace PHPCuong\Faq\Controller\Adminhtml\Faqcat;

use Magento\Backend\App\Action;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PHPCuong_Faq::category_edit';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('PHPCuong_Faq::faqs');
        return $resultPage;
    }

    /**
     * Edit FAQ Category page
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // Get ID and create model
        $id = $this->getRequest()->getParam('category_id');
        $model = $this->_objectManager->create('PHPCuong\Faq\Model\Faqcat');
        $model->setData([]);
        // Initial checking
        if ($id && (int) $id > 0) {
            $model->load($id);
            if (!$model->getCategoryId()) {
                $this->messageManager->addError(__('This Category no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
            $title = $model->getTitle();
        }

        $FormData = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($FormData)) {
            $model->setData($FormData);
        }

        $this->_coreRegistry->register('phpcuong_faqcat', $model);

        // Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Category') : __('New Category'),
            $id ? __('Edit Category') : __('New Category')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('FAQ Categories Manager'));
        $resultPage->getConfig()->getTitle()
            ->prepend($id ? 'Edit: '.$title.' ('.$id.')' : __('New Category'));

        return $resultPage;
    }
}
