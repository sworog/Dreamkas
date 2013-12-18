<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByProducts;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Report\GrossSales\TodayGrossSales;
use DateTime;

class GrossSalesBySubCategories extends TodayGrossSales
{
    /**
     * @var SubCategory
     */
    protected $subCategory;

    /**
     * @param SubCategory $subCategory
     * @param DateTime[] $dates
     */
    public function __construct(SubCategory $subCategory, array $dates)
    {
        $this->subCategory = $subCategory;
        parent::__construct($dates);
    }
}
