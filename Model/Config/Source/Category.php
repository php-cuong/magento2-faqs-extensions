<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-16 16:34:52
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2016-12-18 21:03:33
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
    protected function getCategoriesActive()
    {
        return $this->_faqCategory->getCollection()
        ->addFieldToFilter('is_active', '1')
        ->load()
        ->getData();
    }

    protected function getAllCategories()
    {
        return $this->_faqCategory->getCollection()->load()->getData();
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $model = $this->getAllCategories();
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
        $model = $this->getCategoriesActive();

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
