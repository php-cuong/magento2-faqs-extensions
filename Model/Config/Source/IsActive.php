<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-16 04:12:46
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-17 02:26:08
 */

namespace PHPCuong\Faq\Model\Config\Source;

class IsActive implements \Magento\Framework\Option\ArrayInterface
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('Active')],
            ['value' => 0, 'label' => __('InActive')]
        ];
    }

    public function getStatusOptions()
    {
        $options = [
            self::STATUS_ENABLED => __('Active'),
            self::STATUS_DISABLED => __('InActive'),
        ];

        $this->_options = $options;
        return $this->_options;
    }
}
