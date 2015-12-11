<?php
namespace Celebros\ConversionPro\Model;

class Observer
{
    /**
     * @var \Magento\Framework\App\Action\Context
     */
    protected $context;

    /**
     * @var \Celebros\ConversionPro\Helper\Data
     */
    protected $helper;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Celebros\ConversionPro\Helper\Data $helper)
    {
        $this->context = $context;
        $this->helper = $helper;
    }

    public function addLayoutHandles($observer)
    {
        if (!$this->helper->isEnabledOnFrontend())
            return;

        $layoutUpdate = $observer->getEvent()->getLayout()->getUpdate();
        $fullActionName = $observer->getEvent()->getFullActionName();

        switch ($fullActionName) {
            case 'catalogsearch_result_index':
                $layoutUpdate->addHandle('conversionpro_catalogsearch_result_index');
                break;

            case 'catalog_category_view':
                if ($this->helper->getNavToSearch()) {
                    $categoryId = $this->context->getRequest()->getParam('id');
                    if (!$this->helper->isCategoryIdBlacklisted($categoryId)) {
                    $layoutUpdate->addHandle('conversionpro_catalog_category_view');
                    if ($this->helper->getHideContent())
                        $layoutUpdate->addHandle('conversionpro_catalog_category_view_hide_content');
                    }
                }
                break;
        }
    }

    public function setOneColumnLayout()
    {
        if (!$this->helper->isEnabledOnFrontend())
            return;

        $view = $this->context->getView();
        $page = $view->getPage();

        $layoutUpdate = $page->getLayout()->getUpdate();
        $layoutUpdate->addHandle('conversionpro_catalogsearch_result_index');

        $page->getConfig()->setPageLayout('1column');

    }
}