<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-17 05:09:06
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-01-05 09:10:48
 */

namespace PHPCuong\Faq\Controller\Adminhtml\Faq;

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
            $id = $this->getRequest()->getParam('faq_id');

            /** @var \PHPCuong\Faq\Model\Faq $model */
            $model = $this->_objectManager->create('PHPCuong\Faq\Model\Faq')->load($id);
            if (!$model->getFaqId() && $id) {
                $this->messageManager->addError(__('This FAQ no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            $this->_eventManager->dispatch(
                'faq_faq_prepare_save',
                ['faq' => $model, 'request' => $this->getRequest()]
            );

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the FAQ.'));

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['faq_id' => $model->getFaqId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the FAQ.'));
                // $this->messageManager->addError($e->getMessage());
            }

            $this->_getSession()->setFormData($data);
            if ($this->getRequest()->getParam('faq_id')) {
                return $resultRedirect->setPath('*/*/edit', ['faq_id' => $this->getRequest()->getParam('faq_id')]);
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
