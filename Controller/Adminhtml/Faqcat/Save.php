<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-20 00:23:20
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-20 03:20:34
 */

namespace PHPCuong\Faq\Controller\Adminhtml\Faqcat;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\TestFramework\Inspection\Exception;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    /**
     * @param Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getPostValue();
        if ($data) {

            $id = $this->getRequest()->getParam('category_id');

            /** @var \PHPCuong\Faq\Model\Faqcat $model */
            $model = $this->_objectManager->create('PHPCuong\Faq\Model\Faqcat')->load($id);
            if (!$model->getCategoryId() && $id) {
                $this->messageManager->addError(__('This Category no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the Category.'));

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['category_id' => $model->getCategoryId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Category.'));
            }

            $this->_getSession()->setFormData($data);
            if ($this->getRequest()->getParam('category_id')) {
                return $resultRedirect->setPath('*/*/edit', ['category_id' => $this->getRequest()->getParam('category_id')]);
            }
            return $resultRedirect->setPath('*/*/new');
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Check if admin has permissions to visit related pages.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        if ($this->_authorization->isAllowed('PHPCuong_Faq::faq_edit') || $this->_authorization->isAllowed('PHPCuong_Faq::faq_create')) {
            return true;
        }
        return false;
    }
}
