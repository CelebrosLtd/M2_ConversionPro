<?php

/**
 * Celebros (C) 2023. All Rights Reserved.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish correct extension functionality.
 * If you wish to customize it, please contact Celebros.
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
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\Controller\Result
     */
    protected $rawResultFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $rawResultFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Controller\Result\RawFactory $rawResultFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->rawResultFactory = $rawResultFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context);
    }

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
     * @return array
     */
    public function getSkus(): array
    {
        $skus = explode(',', (string)$this->getRequest()->getParam(self::SKUS_VAR));
        return $skus;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $collection = $this->productCollectionFactory->create();
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

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $products
     * @return array
     */
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
