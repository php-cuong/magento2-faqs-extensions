<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-19 23:29:27
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-01-05 09:17:48
 */

namespace PHPCuong\Faq\Block\Adminhtml\Faqcat\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Internal constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('faqcat_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Category Information'));
    }

    protected function _beforeToHtml()
    {
        $this->setActiveTab('general_section');
        return parent::_beforeToHtml();
    }

    /**
     * Prepare Layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->addTab(
            'general_section',
            [
                'label' => __('General Information'),
                'content' => $this->getLayout()->createBlock('PHPCuong\Faq\Block\Adminhtml\Faqcat\Edit\Tab\General')->toHtml()
            ]
        );

        $this->addTab(
            'optimisation_section',
            [
                'label' => __('Search Engine Optimisation'),
                'content' => $this->getLayout()->createBlock('PHPCuong\Faq\Block\Adminhtml\Faqcat\Edit\Tab\SearchEngineOptimisation')->toHtml()
            ]
        );

        $this->addTab(
            'websites_section',
            [
                'label' => __('FAQ Category in Websites'),
                'content' => $this->getLayout()->createBlock('PHPCuong\Faq\Block\Adminhtml\Faqcat\Edit\Tab\Websites')->toHtml()
            ]
        );

        if ($this->getRequest()->getParam('category_id')) {
            $this->addTab('question_section', ['label' => __('FAQs in Category'), 'url' => $this->getUrl('*/faqcat/question', ['_current' => true]), 'class' => 'ajax']);
        }
        return parent::_prepareLayout();
    }
}
