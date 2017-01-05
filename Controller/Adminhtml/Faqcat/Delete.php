<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-20 01:57:49
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-01-05 09:10:28
 */

namespace PHPCuong\Faq\Controller\Adminhtml\Faqcat;

use PHPCuong\Faq\Model\ResourceModel\Faqcat;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PHPCuong_Faq::category_delete';

    /**
     * Delete FAQ Category
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        // check if we know what should be deleted
        $category_id = $this->getRequest()->getParam('category_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($category_id && (int) $category_id > 0) {
            $title = '';
            try {
                // init model and delete
                $model = $this->_objectManager->create('PHPCuong\Faq\Model\Faqcat');
                $category = $model->load($category_id);

                if ($category->getCategoryId()) {
                    $title = $model->getTitle();

                    $model->delete();

                    $this->messageManager->addSuccess(__('The "'.$title.'" Category has been deleted.'));

                    return $resultRedirect->setPath('*/*/');
                }
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['category_id' => $category_id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('Category to delete was not found.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
