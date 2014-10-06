<?php

namespace Lighthouse\ReportsBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesByProducts\GrossMarginSalesByProductsCollection;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReportManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use DateTime;

class GrossMarginSalesController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.reports.gross_margin_sales.manager")
     * @var GrossMarginSalesReportManager
     */
    protected $grossMarginSalesReportManager;

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
        $storeId = $request->get('store');
        $startDate = new DateTime($request->get('startDate', '-1 week 00:00:00'));
        $endDate = new DateTime($request->get('endDate', 'now'));

        if (null !== $storeId) {
            return $this
                ->grossMarginSalesReportManager
                ->getGrossSalesByProductForStoreReports($group, $storeId, $startDate, $endDate);
        } else {
            return $this
                ->grossMarginSalesReportManager
                ->getGrossSalesByProductForSubCategoryReports($group, $startDate, $endDate);
        }

    }
}
