<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesByCatalogGroups;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\DocumentCollection;

class GrossMarginSalesByCatalogGroupsCollection extends DocumentCollection
{
    /**
     * @param SubCategory $subCategory
     * @return bool
     */
    public function containsCatalogGroup(SubCategory $subCategory)
    {
        return $this->containsKey($subCategory->id);
    }

    /**
     * @param SubCategory $subCategory
     * @return GrossMarginSalesByCatalogGroups
     */
    public function getByCatalogGroup(SubCategory $subCategory)
    {
        if ($this->containsCatalogGroup($subCategory)) {
            return $this->get($subCategory->id);
        } else {
            return $this->createByCatalogGroup($subCategory);
        }
    }

    /**
     * @param SubCategory $subCategory
     * @return GrossMarginSalesByCatalogGroups
     */
    public function createByCatalogGroup(SubCategory $subCategory)
    {
        $report = new GrossMarginSalesByCatalogGroups($subCategory);
        $this->set($subCategory->id, $report);
        return $report;
    }
}
