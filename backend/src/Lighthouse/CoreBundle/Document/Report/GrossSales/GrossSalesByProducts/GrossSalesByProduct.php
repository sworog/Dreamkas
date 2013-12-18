<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByProducts;

use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Report\GrossSales\TodayGrossSales;
use DateTime;

class GrossSalesByProduct extends TodayGrossSales
{
    /**
     * @var StoreProduct
     */
    protected $storeProduct;

    /**
     * @param StoreProduct $product
     * @param DateTime[] $endDayHours
     */
    public function __construct(StoreProduct $product, array $endDayHours)
    {
        $this->storeProduct = $product;
        parent::__construct($endDayHours);
    }
}
