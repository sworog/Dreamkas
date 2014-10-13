<?php

namespace Lighthouse\ReportsBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Lighthouse\CoreBundle\Controller\AbstractRestController;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\GrossMarginSalesFilter;
use Lighthouse\ReportsBundle\Form\GrossMarginSales\GrossMarginSalesFilterType;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\CatalogGroups\GrossMarginSalesByCatalogGroupsCollection;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\Products\GrossMarginSalesByProductsCollection;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReportManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use DateTime;

class GrossMarginSalesController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.reports.gross_margin_sales.manager")
     * @var GrossMarginSalesReportManager
     */
    protected $grossMarginSalesReportManager;

    /**
     * @return FormTypeInterface
     */
    protected function getDocumentFormType()
    {
        return new GrossMarginSalesFilterType();
    }

    /**
     * @param SubCategory $group
     * @param Request $request
     * @return GrossMarginSalesByProductsCollection
     *
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @Rest\Route("catalog/groups/{group}/reports/grossMarginSalesByProduct")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc()
     */
    public function getCatalogGroupReportsGrossMarginSalesByProductAction(SubCategory $group, Request $request)
    {
        $grossMarginSalesReportManager = $this->grossMarginSalesReportManager;
        return $this->processFormCallback(
            $request,
            function (GrossMarginSalesFilter $filter) use ($grossMarginSalesReportManager, $group) {
                return $grossMarginSalesReportManager->getProductsReports($filter, $group);
            }
        );
    }

    /**
     * @param Request $request
     * @return GrossMarginSalesByCatalogGroupsCollection|FormInterface
     *
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @Rest\Route("catalog/groups/reports/grossMarginSalesByCatalogGroup")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc
     */
    public function getCatalogGroupReportsGrossMarginSalesByCatalogGroupAction(Request $request)
    {
        $grossMarginSalesReportManager = $this->grossMarginSalesReportManager;
        return $this->processFormCallback(
            $request,
            function (GrossMarginSalesFilter $filter) use ($grossMarginSalesReportManager) {
                return $grossMarginSalesReportManager->getCatalogGroupsReports($filter);
            }
        );
    }
}
