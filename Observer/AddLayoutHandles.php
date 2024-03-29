<?php

/**
 * Celebros (C) 2023. All Rights Reserved.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish correct extension functionality.
 * If you wish to customize it, please contact Celebros.
 */

namespace Celebros\ConversionPro\Observer;

use Magento\Framework\Event\ObserverInterface;

class AddLayoutHandles implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Action\Context
     */
    public $context;

    /**
     * @var \Celebros\ConversionPro\Helper\Data
     */
    public $helper;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Celebros\ConversionPro\Helper\Data $helper
     * @return void
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Celebros\ConversionPro\Helper\Data $helper
    ) {
        $this->context = $context;
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->helper->isEnabledOnFrontend()) {
            return;
        }

        $layoutUpdate = $observer->getEvent()->getLayout()->getUpdate();
        $fullActionName = $observer->getEvent()->getFullActionName();
        $view = $this->context->getView();
        $page = $view->getPage();
        switch ($fullActionName) {
            case 'catalogsearch_result_index':
                $layoutUpdate->addHandle('conversionpro_catalogsearch_result_index');
                if ($this->helper->getHideContent()) {
                    $layoutUpdate->addHandle('conversionpro_catalog_category_view_hide_content');
                }
                $page->getConfig()->setPageLayout('1column');
                break;

            case 'catalog_category_view':
                if ($this->helper->getNavToSearch()) {
                    $categoryId = $this->context->getRequest()->getParam('id');
                    if (!$this->helper->isCategoryIdBlacklisted($categoryId)) {
                        $layoutUpdate->addHandle('conversionpro_catalog_category_view');
                        if ($this->helper->getHideContent()) {
                            $layoutUpdate->addHandle('conversionpro_catalog_category_view_hide_content');
                        }
                        $page->getConfig()->setPageLayout('1column');
                    }
                }
                break;
        }
    }
}
