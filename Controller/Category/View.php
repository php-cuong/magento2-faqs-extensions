<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-21 16:15:56
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-11-28 19:30:54
 */

namespace PHPCuong\Faq\Controller\Category;

class View extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $_resultForwardFactory;

    /**
     * @var \PHPCuong\Faq\Model\ResourceModel\Faqcat
     */
    protected $_faqCatResourceModel;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     * @param \PHPCuong\Faq\Model\ResourceModel\Faqcat $faqCatResourceModel
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \PHPCuong\Faq\Model\ResourceModel\Faqcat $faqCatResourceModel,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
    ) {
        $this->_faqCatResourceModel  = $faqCatResourceModel;
        $this->_resultForwardFactory = $resultForwardFactory;
        $this->_resultPageFactory    = $resultPageFactory;
        return parent::__construct($context);
    }

    /**
     * View action
     *
     * @return \Magento\Framework\View\Result\PageFactory|\Magento\Framework\Controller\Result\ForwardFactory
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('category_id');
        $textSearch =  $this->getRequest()->getParam('s');
        if ($category = $this->_faqCatResourceModel->getFaqCategoryStore($id, $textSearch)) {
            $resultPage = $this->_resultPageFactory->create();

            $resultPage->getConfig()->getTitle()->set(__('FAQs'));

            $resultPage->getConfig()->getTitle()->prepend(__($category['title']));

            return $resultPage;
        }
        $resultForward = $this->_resultForwardFactory->create();
        return $resultForward->forward('noroute');
    }
}
