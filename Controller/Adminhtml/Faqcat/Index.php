<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-19 22:01:13
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-01-06 08:06:53
 */

namespace PHPCuong\Faq\Controller\Adminhtml\Faqcat;

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
     * FAQ Categories Manager Page
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $resultPage->addBreadcrumb(
            'FAQ Categories Manager',
            'FAQ Categories Manager'
        );
        $resultPage->getConfig()->getTitle()->prepend(__('FAQs'));
        $resultPage->getConfig()->getTitle()
            ->prepend('FAQ Categories Manager');
        return $resultPage;
    }
}
