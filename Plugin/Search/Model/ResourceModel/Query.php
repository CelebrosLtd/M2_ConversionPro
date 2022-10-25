<?php

/*
 * Celebros (C) 2022. All Rights Reserved.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish correct extension functionality.
 * If you wish to customize it, please contact Celebros.
 */

namespace Celebros\ConversionPro\Plugin\Search\Model\ResourceModel;

use Celebros\ConversionPro\Helper\Data as Helper;
use Magento\Search\Model\ResourceModel\Query as MagentoResourceModelQuery;
use Magento\Framework\Model\AbstractModel;
use Magento\Search\Model\Query as QueryModel;

class Query
{
    /**
     * @param \Celebros\ConversionPro\Helper\Data $helper
     * @return void
     */
    public function __construct(
        Helper $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param MagentoResourceModelQuery $resourceQuery
     * @param callable $proceed
     * @param AbstractModel $object
     * @param string $value
     * @return MagentoResourceModelQuery
     */
    public function aroundLoadByQueryText(
        MagentoResourceModelQuery $resourceQuery,
        callable $proceed,
        AbstractModel $object,
        $value
    ) {
        if ($this->helper->isEnabled()) {
            return $resourceQuery;
        }

        return $proceed($object, $value);
    }
}
