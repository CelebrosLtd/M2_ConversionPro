<?php
/**
 * Celebros
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish correct extension functionality.
 * If you wish to customize it, please contact Celebros.
 *
 ******************************************************************************
 * @category    Celebros
 * @package     Celebros_ConversionPro
 */
namespace Celebros\ConversionPro\Model\Config\Source;

class NavToSearchBlacklist implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\Collection
     */
    public $categoryCollection;
    
    /**
     * @var array
     */
    public $options;
    
    public function __construct(\Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection)
    {
        $this->categoryCollection = $categoryCollection;
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