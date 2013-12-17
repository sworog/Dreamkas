<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByProducts;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;

class GrossSalesByProduct extends AbstractDocument
{
    /**
     * @var StoreProduct
     */
    protected $storeProduct;

    /**
     * @var GrossSalesByProductDay
     */
    protected $today;

    /**
     * @var GrossSalesByProductDay
     */
    protected $yesterday;

    /**
     * @var GrossSalesByProductDay
     */
    protected $weekAgo;

    public function __construct(StoreProduct $product, $endDayHours)
    {
        $this->storeProduct = $product;
        foreach ($endDayHours as $key => $dayHour) {
            $this->$key = new GrossSalesByProductDay($dayHour);
        }
    }
}
