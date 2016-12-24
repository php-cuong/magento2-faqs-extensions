<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-17 00:18:36
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-24 15:23:33
 */

namespace PHPCuong\Faq\Block\Adminhtml\Faq\Edit\Tab;

class General extends \Magento\Backend\Block\Widget\Form\Generic implements
    \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Active or InActive
     *
     * @var \PHPCuong\Faq\Model\Config\Source\IsActive
     */
    protected $_status;

    /**
     * Yes or No
     *
     * @var \PHPCuong\Faq\Model\Config\Source\Yesno
     */
    protected $_yesNo;

    /**
     * Category
     *
     * @var \PHPCuong\Faq\Model\Config\Source\Category
     */
    protected $_category;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @param \PHPCuong\Faq\Model\Config\Source\Category $category
     * @param \PHPCuong\Faq\Model\Config\Source\Yesno $yesNo
     * @param \PHPCuong\Faq\Model\Config\Source\IsActive $status
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \PHPCuong\Faq\Model\Config\Source\Category $category,
        \PHPCuong\Faq\Model\Config\Source\Yesno $yesNo,
        \PHPCuong\Faq\Model\Config\Source\IsActive $status,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {
        $this->_category = $category;
        $this->_yesNo    = $yesNo;
        $this->_status   = $status;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setActive(true);
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('General Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Returns status flag about this tab can be showen or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('General Information')]);

        $this->_addElementTypes($fieldset);

        $fieldset->addField(
            'is_active',
            'select',
            [
                'name' => 'is_active',
                'label' => __('Status'),
                'title' => __('Status'),
                'required' => true,
                'values' => $this->_status->getStatusOptions()
            ]
        );

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Title'),
                'title' => __('Title'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'most_frequently',
            'select',
            [
                'name'   => 'most_frequently',
                'label'  => __('Most frequently'),
                'title'  => __('Most frequently'),
                'values' => $this->_yesNo->getYesnoOptions()
            ]
        );

        $fieldset->addField(
            'category_id',
            'select',
            [
                'name'   => 'category_id',
                'label'  => __('Category'),
                'title'  => __('Category'),
                'values' => $this->_category->getCategoryOptions(),
                'required' => true
            ]
        );

        $fieldset->addField(
            'sort_order',
            'text',
            [
                'name'  => 'sort_order',
                'label' => __('Sort Order'),
                'title' => __('Sort Order'),
                'size'  => '10'
            ]
        );

        $formData = $this->_coreRegistry->registry('phpcuong_faq');
        if ($formData) {
            if ($formData->getFaqId()) {
                $fieldset->addField(
                    'faq_id',
                    'hidden',
                    ['name' => 'faq_id']
                );
            }
            if ($formData->getIsActive() == null) {
                $formData->setIsActive('1');
            }
            $form->setValues($formData->getData());
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
