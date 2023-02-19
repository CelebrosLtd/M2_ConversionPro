<?php

/**
 * Celebros (C) 2023. All Rights Reserved.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish correct extension functionality.
 * If you wish to customize it, please contact Celebros.
 */

namespace Celebros\ConversionPro\Block\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Info extends Field
{
    public const MODULE_NAME = 'Celebros_ConversionPro';
    protected $_moduleDb;

    public function __construct(
        \Magento\Framework\Module\ResourceInterface $moduleDb
    ) {
        $this->_moduleDb = $moduleDb;
    }

    public function render(AbstractElement $element)
    {
        $id = $element->getHtmlId();
        $html = '<tr id="row_' . $id . '">';
        $html .= '<td class="label">' . __('Module Version') . '</td><td class="value">'
            . $this->getModuleVersion() . '</td><td class="scope-label"></td>';
        $html .= '</tr>';

        return $html;
    }

    public function getModuleVersion()
    {
        return $this->_moduleDb->getDbVersion(self::MODULE_NAME);
    }
}
