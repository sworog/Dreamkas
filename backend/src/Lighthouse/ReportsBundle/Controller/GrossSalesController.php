<?php

namespace Lighthouse\ReportsBundle\Controller;

use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSales\GrossSales;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByCategories\GrossSalesByCategoriesCollection;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByGroups\GrossSalesByGroupsCollection;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByProducts\GrossSalesByProductsCollection;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesBySubCategories\GrossSalesBySubCategoriesCollection;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByStores\GrossSalesByStoresCollection;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesReportManager;
use Lighthouse\ReportsBundle\Reports\GrossSales\TodayHoursGrossSales;
use Lighthouse\CoreBundle\Document\Store\Store;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ODM\MongoDB\Cursor;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use DateTime;

class GrossSalesController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.reports.gross_sales.manager")
     * @var GrossSalesReportManager
     */
    protected $grossSalesReportManager;

    /**
     * @param Store $store
     * @param Request $request
     * @return GrossSalesStoreTodayReport
     *
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER")
     * @Rest\Route("stores/{store}/reports/grossSales")
     * @ApiDoc
     */
    public function getStoreReportsGrossSalesAction(Store $store, Request $request)
    {
        $time = new DateTime($request->get('time', 'now'));
        return $this->grossSalesReportManager->getGrossSalesStoreReport($store, $time);
    }

    /**
     * @param Store $store
     * @param Request $request
     * @return TodayHoursGrossSales
     *
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER")
     * @Rest\Route("stores/{store}/reports/grossSalesByHours")
     * @ApiDoc
     */
    public function getStoreReportsGrossSalesByHoursAction(Store $store, Request $request)
    {
        $time = new DateTime($request->get('time', 'now'));
        return $this->grossSalesReportManager->getGrossSalesStoreByHours($store, $time);
    }

    /**
     * @param Request $request
     * @return GrossSalesByStoresCollection
     *
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @Rest\Route("reports/grossSalesByStores")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc
     */
    public function getReportsGrossSalesByStoresAction(Request $request)
    {
        $time = new DateTime($request->get('time', 'now'));
        return $this->grossSalesReportManager->getGrossSalesByStores($time);
    }

    /**
     * @param Request $request
     * @return GrossSales
     *
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @Rest\Route("reports/grossSales")
     * @ApiDoc
     */
    public function getReportsGrossSalesAction(Request $request)
    {
        $time = new DateTime($request->get('time', 'now'));
        return $this->grossSalesReportManager->getGrossSales($time);
    }

    /**
     * @param Store $store
     * @param SubCategory $subCategory
     * @param Request $request
     * @return GrossSalesByProductsCollection
     *
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER")
     * @Rest\Route("stores/{store}/subcategories/{subCategory}/reports/grossSalesByProducts")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc
     */
    public function getReportsGrossSalesByProductsAction(Store $store, SubCategory $subCategory, Request $request)
    {
        $time = new DateTime($request->get('time', 'now'));
        return $this->grossSalesReportManager->getGrossSalesByProducts($store, $subCategory, $time);
    }

    /**
     * @param Store $store
     * @param Category $category
     * @param Request $request
     * @return GrossSalesBySubCategoriesCollection
     *
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER")
     * @Rest\Route("stores/{store}/categories/{category}/reports/grossSalesBySubCategories")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc
     */
    public function getReportsGrossSalesBySubCategoriesAction(Store $store, Category $category, Request $request)
    {
        $time = new DateTime($request->get('time', 'now'));
        return $this->grossSalesReportManager->getGrossSalesBySubCategories($store, $category, $time);
    }

    /**
     * @param Store $store
     * @param Group $group
     * @param Request $request
     * @return GrossSalesByCategoriesCollection
     *
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER")
     * @Rest\Route("stores/{store}/groups/{group}/reports/grossSalesByCategories")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc
     */
    public function getReportsGrossSalesByCategoriesAction(Store $store, Group $group, Request $request)
    {
        $time = new DateTime($request->get('time', 'now'));
        return $this->grossSalesReportManager->getGrossSalesByCategories($store, $group, $time);
    }

    /**
     * @param Store $store
     * @param Request $request
     * @return GrossSalesByGroupsCollection
     *
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER")
     * @Rest\Route("stores/{store}/reports/grossSalesByGroups")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc
     */
    public function getReportsGrossSalesByGroupsAction(Store $store, Request $request)
    {
        $time = new DateTime($request->get('time', 'now'));
        return $this->grossSalesReportManager->getGrossSalesByGroups($store, $time);
    }
}
