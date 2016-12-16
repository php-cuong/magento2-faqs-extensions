<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-16 16:34:52
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-17 03:02:37
 */

namespace PHPCuong\Faq\Model\Config\Source;

class Category implements \Magento\Framework\Option\ArrayInterface
{
    protected $_faqCategory;

    public function __construct(
        \PHPCuong\Faq\Model\Faqcat $faqCat
    ) {
        $this->_faqCategory = $faqCat;
    }
    protected function getCategories()
    {
        return $this->_faqCategory->getCollection()->load()->getData();
    }
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $model = $this->getCategories();
        $results = [];
        $results[] = [
            'value' => '0',
            'label' => 'All Categories'
        ];
        foreach ($model as $value) {
            $results[] = [
                'value' => $value['category_id'],
                'label' => $value['title']
            ];
        }
        return $results;
    }
    /**
     * Options getter
     *
     */
    public function getCategoryOptions()
    {
        $model = $this->getCategories();

        $options = [
            '' => '-- Select a category --'
        ];

        foreach ($model as $value) {
            $arg = [
                $value['category_id'] => $value['title']
            ];
            $options = $options + $arg;
        }

        $this->_options = $options;
        return $this->_options;
    }
}
