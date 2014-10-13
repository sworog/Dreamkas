<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales\Products;

use Lighthouse\CoreBundle\Document\DocumentCollection;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;

class GrossMarginSalesByProductsCollection extends DocumentCollection
{
    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @param NumericFactory $numericFactory
     */
    public function __construct(NumericFactory $numericFactory)
    {
        parent::__construct();

        $this->numericFactory = $numericFactory;
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function containsProduct(Product $product)
    {
        return $this->containsKey($product->id);
    }

    /**
     * @param Product $product
     * @return GrossMarginSalesByProducts
     */
    public function getByProduct(Product $product)
    {
        if ($this->containsProduct($product)) {
            return $this->get($product->id);
        } else {
            return $this->createByProduct($product);
        }
    }

    /**
     * @param Product $product
     * @return GrossMarginSalesByProducts
     */
    public function createByProduct(Product $product)
    {
        $report = new GrossMarginSalesByProducts($product);
        $report->setReportValues(
            $this->numericFactory->createMoney(0),
            $this->numericFactory->createMoney(0),
            $this->numericFactory->createMoney(0),
            $this->numericFactory->createQuantity(0)
        );
        $this->set($product->id, $report);
        return $report;
    }

    /**
     * @param Product[] $products
     * @return GrossMarginSalesByProductsCollection
     */
    public function fillByProducts($products)
    {
        foreach ($products as $product) {
            if (!$this->containsProduct($product)) {
                $this->createByProduct($product);
            }
        }
        return $this;
    }
}
