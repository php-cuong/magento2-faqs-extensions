<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-17 01:32:56
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-24 15:24:33
 */

namespace PHPCuong\Faq\Block\Adminhtml\Faq\Edit\Tab;

class SearchEngineOptimisation extends \Magento\Backend\Block\Widget\Form\Generic implements
    \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {
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
        return __('Search Engine Optimisation');
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

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Search Engine Optimisation')]
        );

        $this->_addElementTypes($fieldset);

        $fieldset->addField(
            'identifier',
            'text',
            [
                'name' => 'identifier',
                'label' => __('URL Key'),
                'title' => __('URL Key')
            ]
        );

        $fieldset->addField(
            'meta_keywords',
            'textarea',
            [
                'name'  => 'meta_keywords',
                'label' => __('Meta Keywords'),
                'title' => __('Meta Keywords'),
                'cols'  => '15',
                'row'   => '2'
            ]
        );

        $fieldset->addField(
            'meta_description',
            'textarea',
            [
                'name'  => 'meta_description',
                'label' => __('Meta Description'),
                'title' => __('Meta Description'),
                'cols'  => '15',
                'row'   => '2'
            ]
        );

        $formData = $this->_coreRegistry->registry('phpcuong_faq');
        if ($formData) {
            $form->setValues($formData->getData());
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
