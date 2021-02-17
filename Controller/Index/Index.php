<?php

/**
 * BelVG LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 *
 ********************************************************************
 * @category   BelVG
 * @package    BelVG_Pricelist
 * @copyright  Copyright (c) 2010 - 2015 BelVG LLC. (http://www.belvg.com)
 * @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 */

namespace Celebros\ConversionPro\Controller\Index;

use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;

class Index extends \Magento\Framework\App\Action\Action implements CsrfAwareActionInterface
{
    public const SKUS_VAR = 'skus';

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
     * @var \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @return void
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
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

    /**
     * @param string $subject
     * @return bool
     */
    public function isValidCallback(string $subject): bool
    {
        $identifier_syntax = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*+$/u';
        $reserved_words = array('break', 'do', 'instanceof', 'typeof', 'case',
          'else', 'new', 'var', 'catch', 'finally', 'return', 'void', 'continue',
          'for', 'switch', 'while', 'debugger', 'function', 'this', 'with',
          'default', 'if', 'throw', 'delete', 'in', 'try', 'class', 'enum',
          'extends', 'super', 'const', 'export', 'import', 'implements', 'let',
          'private', 'public', 'yield', 'interface', 'package', 'protected',
          'static', 'null', 'true', 'false');

        return preg_match($identifier_syntax, $subject)
            && !in_array(mb_strtolower($subject, 'UTF-8'), $reserved_words);
    }

    public function execute()
    {
        $collection = $this->_objectManager
            ->create('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory')
            ->create();
        $collection->setFlag('has_stock_status_filter', true);
        $collection->addFieldToFilter('sku', array('in' => $this->getSkus()))
            ->addPriceData();

        if (isset($_GET['callback']) /*&& $this->isValidCallback($_GET['callback'])*/) {
            header('content-type: application/javascript; charset=utf-8');
            exit("{$_GET['callback']}(" . json_encode($this->preparePrices($collection)) . ")");
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
