<?php

namespace Lighthouse\ReportsBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
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
     * @param Store $store
     * @param Request $request
     * @return GrossMarginSalesByProductsCollection
     *
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER")
     * @Rest\Route("stores/{store}/reports/grossMarginSalesByProduct")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc()
     */
    public function getReportsGrossMarginSalesByProductAction(Store $store, Request $request)
    {
        $startDate = new DateTime($request->get('startDate', '-1 week 00:00:00'));
        $endDate = new DateTime($request->get('endDate', 'now'));

        return $this->grossMarginSalesReportManager->getGrossSalesByProductReports($store, $startDate, $endDate);
    }
}
