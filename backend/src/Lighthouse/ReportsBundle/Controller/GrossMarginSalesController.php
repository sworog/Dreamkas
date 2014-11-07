<?php

namespace Lighthouse\ReportsBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Lighthouse\CoreBundle\Controller\AbstractRestController;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\GrossMarginSalesFilter;
use Lighthouse\ReportsBundle\Form\GrossMarginSales\GrossMarginSalesFilterType;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\CatalogGroups\GrossMarginSalesByCatalogGroupsCollection;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\Network\GrossMarginSalesByNetwork;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\Products\GrossMarginSalesByProductsCollection;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReportManager;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\Stores\GrossMarginSalesByStoresCollection;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;

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
     * @Rest\Route("reports/gross/catalog/groups/{group}/products")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc
     */
    public function getCatalogGroupProductsGrossReportAction(SubCategory $group, Request $request)
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
     * @Rest\Route("reports/gross/catalog/groups")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc
     */
    public function getCatalogGroupsGrossReportAction(Request $request)
    {
        $grossMarginSalesReportManager = $this->grossMarginSalesReportManager;
        return $this->processFormCallback(
            $request,
            function (GrossMarginSalesFilter $filter) use ($grossMarginSalesReportManager) {
                return $grossMarginSalesReportManager->getCatalogGroupsReports($filter);
            }
        );
    }

    /**
     * @param Request $request
     * @return GrossMarginSalesByStoresCollection|FormInterface
     *
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @Rest\Route("reports/gross/stores")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc
     */
    public function getStoresGrossReportAction(Request $request)
    {
        $grossMarginSalesReportManager = $this->grossMarginSalesReportManager;
        return $this->processFormCallback(
            $request,
            function (GrossMarginSalesFilter $filter) use ($grossMarginSalesReportManager) {
                return $grossMarginSalesReportManager->getStoreReports($filter);
            }
        );
    }

    /**
     * @param Request $request
     * @return GrossMarginSalesByNetwork|FormInterface
     *
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @Rest\Route("reports/gross")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc
     */
    public function getGrossReportAction(Request $request)
    {
        $grossMarginSalesReportManager = $this->grossMarginSalesReportManager;
        return $this->processFormCallback(
            $request,
            function (GrossMarginSalesFilter $filter) use ($grossMarginSalesReportManager) {
                return $grossMarginSalesReportManager->getNetworkReport($filter);
            }
        );
    }
}
