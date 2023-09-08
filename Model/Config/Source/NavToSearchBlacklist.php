<?php

/**
 * Celebros (C) 2023. All Rights Reserved.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish correct extension functionality.
 * If you wish to customize it, please contact Celebros.
 */

namespace Celebros\ConversionPro\Model\Config\Source;

class NavToSearchBlacklist implements \Magento\Framework\Option\ArrayInterface
{
    public const CAT_RECURSION_LEVEL = 20;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    public $request;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    public $category;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;

    /**
     * @var array
     */
    public $options;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    public $categoryFactory;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Catalog\Model\CategoryFactory $category,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->request = $request;
        $this->category = $category;
        $this->storeManager = $storeManager;
    }

    public function toOptionArray($isMultiselect = false)
    {
        $result = $this->_toOptionArray();
        if (!$isMultiselect) {
            array_unshift($result, ['value' => '', 'label' => __('--Please Select--')]);
        }

        return $result;
    }

    protected function _toOptionArray()
    {
        if (null === $this->options) {
            $this->options = [];
            $storeId = $this->request->getParam('store', null);
            $store = $this->storeManager->getStore($storeId);
            foreach ($this->getStoreCategories($store) as $category) {
                $this->options[] = [
                    'value' => $category->getId(),
                    'label' => $category->getName(),
                    'style' => 'padding-left: ' . $this->calculatePadding($category->getLevel()) . 'px;'
                ];
            }
        }

        return $this->options;
    }

    public function calculatePadding($level)
    {
        return (int)(($level - 2 ) * 10);
    }

    public function getStoreCategories($store)
    {
        $category = $this->category->create();
        $storeCategories = $category->getCategories(
            $store->getRootCategoryId(),
            self::CAT_RECURSION_LEVEL,
            'path',
            true,
            true
        );

        return $storeCategories;
    }
}
