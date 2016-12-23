<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-24 00:42:40
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-24 00:57:32
 */

namespace PHPCuong\Faq\Block\Links;

class Link extends \Magento\Framework\View\Element\Html\Link
{
    protected $_configHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \PHPCuong\Faq\Helper\Config $configHelper,
        array $data = []
    ) {
        $this->_configHelper = $configHelper;
        parent::__construct($context, $data);
    }

    public function getHref()
    {
        return $this->_configHelper->getFaqPage();
    }

    public function getLabel()
    {
         return __('Frequently Asked Questions');
    }
}
