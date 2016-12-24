<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-16 04:43:54
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-24 17:42:16
 */

namespace PHPCuong\Faq\Model\Config\Source\Admin;

class User implements \Magento\Framework\Option\ArrayInterface
{
    /**
     *
     * @var \Magento\User\Model\UserFactory
     */
    protected $userFactory;

    /**
     *
     * @param \Magento\User\Model\UserFactory $userFactory
     */
    public function __construct(
        \Magento\User\Model\UserFactory $userFactory
    ) {
        $this->userFactory = $userFactory;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $admin_user = $this->userFactory->create()->getCollection()->load()->getData();
        foreach ($admin_user as $value) {
            $results[] = [
                'value' => $value['user_id'],
                'label' => trim($value['firstname'].' '.$value['lastname'])
            ];
        }
        return $results;
    }
}
