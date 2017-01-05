<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-16 17:41:25
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-01-05 09:19:37
 */

namespace PHPCuong\Faq\Block\Adminhtml\Faq;

use Magento\Backend\Block\Widget\Form\Container;

class Edit extends Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Department edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'phpcuong_faq_id';
        $this->_blockGroup = 'PHPCuong_Faq';
        $this->_controller = 'adminhtml_faq';
        parent::_construct();
    }

    /**
     * Prepare layout.
     * Adding save_and_continue button
     *
     * @return $this
     */
    protected function _preparelayout()
    {
        if ($this->_isAllowedAction('PHPCuong_Faq::faq_create') || $this->_isAllowedAction('PHPCuong_Faq::faq_edit')) {
            $this->buttonList->update('save', 'label', __('Save FAQ'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => [
                                'event' => 'saveAndContinueEdit',
                                'target' => '#edit_form'
                            ],
                        ],
                    ]
                ],
                -100
            );

            $faq = $this->_coreRegistry->registry('phpcuong_faq');
            if (!empty($faq)) {
                if ($faq->getFaqId() && $this->_isAllowedAction('PHPCuong_Faq::faq_delete')) {
                    $this->buttonList->add(
                        'delete',
                        [
                            'label'   => __('Delete'),
                            'class'   => 'delete',
                            'onclick' => 'deleteConfirm("Are you sure you want to delete this FAQ?", "'.$this->getDeleteUrl().'")'
                        ],
                        -100
                    );
                }
            }
        } else {
            $this->removeButton('save');
        }
        return parent::_prepareLayout();
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Retrieve delete Url.
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl(
            '*/*/delete',
            [
                '_current' => true,
                'id' => $this->getRequest()->getParam('faq_id')
            ]
        );
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl(
            '*/*/save',
            [
                '_current' => true,
                'back' => 'edit',
                'active_tab' => ''
            ]
        );
    }
}
