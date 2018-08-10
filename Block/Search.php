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
namespace Celebros\ConversionPro\Block;

use Magento\CatalogSearch\Helper\Data;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Search\Model\QueryFactory;

class Search extends Template
{
    /**
     * Catalog search data
     *
     * @var Data
     */
    protected $catalogSearchData;

    public function __construct(
        Context $context,
        Data $catalogSearchData,
        array $data = []
    ) {
        $this->catalogSearchData = $catalogSearchData;
        parent::__construct($context, $data);
    }

    /**
     * Prepare layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $title = $this->getSearchQueryText();
        $this->pageConfig->getTitle()->set($title);

        return parent::_prepareLayout();
    }

    /**
     * Get search query text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getSearchQueryText()
    {
        return __("Search results for: '%1'", $this->catalogSearchData->getEscapedQueryText());
    }
}
