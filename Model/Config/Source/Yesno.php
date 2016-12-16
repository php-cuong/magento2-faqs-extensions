<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-17 02:25:42
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-17 02:29:24
 */

namespace PHPCuong\Faq\Model\Config\Source;

class Yesno implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [];
    }
    /**
     * Options getter
     *
     */
    public function getYesnoOptions()
    {
        $options = [
            '1' => __('Yes'),
            '0' => __('No'),
        ];

        $this->_options = $options;
        return $this->_options;
    }
}
