<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales\CatalogGroups;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\DocumentCollection;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\CatalogGroup\GrossMarginSalesCatalogGroup;

class GrossMarginSalesByCatalogGroupsCollection extends DocumentCollection
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
     * @param SubCategory $catalogGroup
     * @return bool
     */
    public function containsCatalogGroup(SubCategory $catalogGroup)
    {
        return $this->containsKey($catalogGroup->id);
    }

    /**
     * @param SubCategory $catalogGroup
     * @return GrossMarginSalesByCatalogGroups
     */
    public function getByCatalogGroup(SubCategory $catalogGroup)
    {
        if ($this->containsCatalogGroup($catalogGroup)) {
            return $this->get($catalogGroup->id);
        } else {
            return $this->createByCatalogGroup($catalogGroup);
        }
    }

    /**
     * @param SubCategory $subCategory
     * @return GrossMarginSalesByCatalogGroups
     */
    public function createByCatalogGroup(SubCategory $subCategory)
    {
        $report = new GrossMarginSalesByCatalogGroups($subCategory);
        $report->setReportValues(
            $this->numericFactory->createMoney(0),
            $this->numericFactory->createMoney(0),
            $this->numericFactory->createMoney(0),
            $this->numericFactory->createQuantity(0)
        );
        $this->set($subCategory->id, $report);
        return $report;
    }

    /**
     * @param SubCategory[] $catalogGroups
     * @return GrossMarginSalesByCatalogGroupsCollection
     */
    public function fillByCatalogGroups($catalogGroups)
    {
        foreach ($catalogGroups as $catalogGroup) {
            if (!$this->containsCatalogGroup($catalogGroup)) {
                $this->createByCatalogGroup($catalogGroup);
            }
        }
        return $this;
    }

    /**
     * @param GrossMarginSalesCatalogGroup $report
     */
    public function addReportValues(GrossMarginSalesCatalogGroup $report)
    {
        $this->getByCatalogGroup($report->subCategory)->addReportValues($report);
    }
}
