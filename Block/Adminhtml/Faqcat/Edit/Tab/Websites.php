<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-19 23:54:01
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-24 15:30:44
 */

namespace PHPCuong\Faq\Block\Adminhtml\Faqcat\Edit\Tab;

use Magento\Store\Model\Store;

class Websites extends \Magento\Backend\Block\Widget\Form\Generic implements
    \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
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
        return __('FAQ Category in Websites');
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

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('FAQ Category in Websites')]);

        $this->_addElementTypes($fieldset);

        $formData = $this->_coreRegistry->registry('phpcuong_faqcat');

        $field = $fieldset->addField(
            'stores',
            'multiselect',
            [
                'label' => __('Stores View'),
                'title' => __('Stores View'),
                'required' => true,
                'name' => 'stores[]',
                'values' => $this->_systemStore->getStoreValuesForForm(false, true)
            ]
        );
        
        $renderer = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
        );

        $field->setRenderer($renderer);

        $formData->setSelectStores($formData->getStores());

        if ($formData) {
            if ($formData->getStores() == null) {
                $formData->setStores([Store::DEFAULT_STORE_ID]);
            }
            $form->setValues($formData->getData());
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
