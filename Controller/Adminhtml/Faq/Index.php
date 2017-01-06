<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-16 01:57:35
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-01-06 07:51:01
 */

namespace PHPCuong\Faq\Controller\Adminhtml\Faq;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    /**
     * FAQs Manager Page
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $resultPage->addBreadcrumb(
            'FAQs Manager',
            'FAQs Manager'
        );
        $resultPage->getConfig()->getTitle()->prepend(__('FAQs'));
        $resultPage->getConfig()->getTitle()
            ->prepend('FAQs Manager');
        return $resultPage;
    }
}
