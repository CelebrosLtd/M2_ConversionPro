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

class DomLocations implements \Magento\Framework\Option\ArrayInterface
{
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
    public $options = [
        'head' => 'Head',
        'body' => 'Body'
    ];
    
    
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
    
    public function toOptionArray($isMultiselect = false, $req = false)
    {
        $result = $this->_toOptionArray();
        if (!$isMultiselect && $req) {
            array_unshift($result, ['value' => '', 'label' => __('--Please Select--')]);
        }
        
        return $result;
    }
    
    protected function _toOptionArray()
    {
        if (null === $this->options) {
            $this->options = [];
            foreach ($this->options as $key => $label) {
                $this->options[] = [
                    'value' => $key,
                    'label' => __($label)
                ];
            }
        }
        
        return $this->options;
    }
}
