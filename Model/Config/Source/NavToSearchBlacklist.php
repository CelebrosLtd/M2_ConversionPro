<?php
namespace Celebros\ConversionPro\Model\Config\Source;

class NavToSearchBlacklist implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Catalog\Model\Resource\Category\Collection
     */
    protected $categoryCollection;

    /**
     * @var array
     */
    protected $options;

    public function __construct(\Magento\Catalog\Model\Resource\Category\Collection $categoryCollection)
    {
        $this->categoryCollection = $categoryCollection;
    }

    public function toOptionArray($isMultiselect = false)
    {
        $options = $this->_toOptionArray();
        if (!$isMultiselect)
            array_unshift($options, ['value' => '', 'label' => __('--Please Select--')]);
        return $options;
    }

    protected function _toOptionArray()
    {
        if (is_null($this->options)) {
            $this->options = array();
            $this->categoryCollection->addAttributeToSelect('name');
            $this->categoryCollection->setOrder('name');
            foreach ($this->categoryCollection as $category)
                $this->options[] = [
                    'value' => $category->getId(),
                    'label' => $category->getName()];
        }
        return $this->options;
    }
}