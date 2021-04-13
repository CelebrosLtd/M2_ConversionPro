<?php

/**
 * Celebros
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish correct extension functionality.
 * If you wish to customize it, please contact Celebros.
 *
 * *****************************************************************************
 * @category    Celebros
 * @package     Celebros_ConversionPro
 */

namespace Celebros\ConversionPro\Controller\Index;

use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\Action\Action;

class Index extends Action implements CsrfAwareActionInterface
{
    public const SKUS_VAR = 'skus';
    public const CALLBACK_VAR = 'callback';

    /**
     * @param RequestInterface $request
     * @return null|InvalidRequestException
     */
    public function createCsrfValidationException(
        RequestInterface $request
    ): ?InvalidRequestException {
        return null;
    }

    /**
     * @param RequestInterface $request
     * @return bool
     */
    public function validateForCsrf(
        RequestInterface $request
    ): ?bool {
        return true;
    }

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\Controller\Result
     */
    protected $rawResultFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Controller\Result $rawResultFactory
     * @return void
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Controller\Result\RawFactory $rawResultFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->rawResultFactory = $rawResultFactory;
        parent::__construct($context);
    }

    /**
     * @return array
     */
    public function getSkus(): array
    {
        $skus = explode(',', $this->getRequest()->getParam(self::SKUS_VAR));
        return $skus;
    }

    public function execute()
    {
        $collection = $this->_objectManager
            ->create(\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory::class)
            ->create();
        $collection->setFlag('has_stock_status_filter', true);
        $collection->addFieldToFilter('sku', ['in' => $this->getSkus()])
            ->addPriceData();

        if ($callback = $this->getRequest()->getParam(self::CALLBACK_VAR)) {
            $result = $this->rawResultFactory->create();
            $result->setHeader('Content-Type', 'application/javascript; charset=utf-8');
            $result->setContents("{$callback}(" . json_encode($this->preparePrices($collection)) . ")");
            return $result;
        }

        return $this->resultJsonFactory
            ->create()
            ->setData(
                $this->preparePrices($collection)
            );
    }

    public function preparePrices($products)
    {
        $result = [];
        foreach ($products as $sku) {
            $result[$sku->getSku()] = [
                'minimal_price' => $sku->getMinPrice(),
                'regular_price' => $sku->getPrice(),
                'special_price' => $sku->getSpecialPrice()
            ];
        }

        return $result;
    }
}
