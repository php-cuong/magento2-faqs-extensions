<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-16 02:09:30
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-01-05 08:44:04
 */

namespace PHPCuong\Faq\Ui\Component\Listing\Column;

use \PHPCuong\Faq\Model\ResourceModel\Faq as faqResourceModel;

class FaqActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Url path  to edit
     *
     * @var string
     */
    const FAQ_URL_PATH_EDIT = 'phpcuong/faq/edit';

    /**
     * Url path to delete
     *
     * @var string
     */
    const FAQ_URL_PATH_DELETE = 'phpcuong/faq/delete';

    /**
     * URL builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @var \Magento\Cms\Block\Adminhtml\Page\Grid\Renderer\Action\UrlBuilder
     */
    protected $_actionUrlBuilder;

    /**
     * constructor
     *
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Cms\Block\Adminhtml\Page\Grid\Renderer\Action\UrlBuilder $actionUrlBuilder
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Cms\Block\Adminhtml\Page\Grid\Renderer\Action\UrlBuilder $actionUrlBuilder,
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->_urlBuilder = $urlBuilder;
        $this->_actionUrlBuilder = $actionUrlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['faq_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->_urlBuilder->getUrl(self::FAQ_URL_PATH_EDIT, ['faq_id' => $item['faq_id']]),
                        'label' => __('Edit')
                    ];
                    $item[$name]['delete'] = [
                        'href' => $this->_urlBuilder->getUrl(self::FAQ_URL_PATH_DELETE, ['faq_id' => $item['faq_id']]),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete "${ $.$data.title }"'),
                            'message' => __('Are you sure you wan\'t to delete this question?')
                        ]
                    ];
                }

                if (isset($item['identifier'])) {
                    $item[$name]['preview'] = [
                        'href' => $this->_actionUrlBuilder->getUrl(faqResourceModel::FAQ_QUESTION_PATH.'/'.$item['identifier'].faqResourceModel::FAQ_DOT_HTML, isset($item['store_id']) ? $item['store_id'] : null, null),
                        'label' => __('View')
                    ];
                }
            }
        }
        return $dataSource;
    }
}
