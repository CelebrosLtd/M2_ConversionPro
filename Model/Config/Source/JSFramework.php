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

class JSFramework implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    public $request;

    /**
     * @var array
     */
    public $options = [
        'jquery' => 'jQuery',
        'angular' => 'Angular JS'
    ];

    public function __construct(
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->request = $request;
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
