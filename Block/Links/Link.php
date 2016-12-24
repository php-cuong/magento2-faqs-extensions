<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-24 00:42:40
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-24 16:22:52
 */

namespace PHPCuong\Faq\Block\Links;

class Link extends \Magento\Framework\View\Element\Html\Link
{
    /**
     * @var \PHPCuong\Faq\Helper\Config
     */
    protected $_configHelper;

    /**
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \PHPCuong\Faq\Helper\Config $configHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \PHPCuong\Faq\Helper\Config $configHelper,
        array $data = []
    ) {
        $this->_configHelper = $configHelper;
        parent::__construct($context, $data);
    }

    /**
     * Prepare url using passed id path and return it
     * or return false if path was not found in url rewrites.
     *
     * @throws \RuntimeException
     * @return string|false
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getHref()
    {
        return $this->_configHelper->getFaqPage();
    }

    /**
     * Return label
     *
     * @return string
     */
    public function getLabel()
    {
         return __('Frequently Asked Questions');
    }
}
