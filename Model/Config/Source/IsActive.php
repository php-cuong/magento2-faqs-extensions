<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-16 04:12:46
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-27 08:14:19
 */

namespace PHPCuong\Faq\Model\Config\Source;

class IsActive implements \Magento\Framework\Option\ArrayInterface
{
    const STATUS_ENABLED = 1;

    const STATUS_DISABLED = 0;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('Active')],
            ['value' => 0, 'label' => __('InActive')]
        ];
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function getStatusOptions($flag = false)
    {
        $options = [];

        if ($flag) {
            $options[''] = '-- Status --';
        }

        $options[self::STATUS_DISABLED] = __('InActive');
        $options[self::STATUS_ENABLED] = __('Active');

        $this->_options = $options;
        return $this->_options;
    }
}
