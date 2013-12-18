<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSales\GrossSales;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByCategories\GrossSalesByCategoriesCollection;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByGroups\GrossSalesByGroupsCollection;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByProducts\GrossSalesByProductsCollection;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesBySubCategories\GrossSalesBySubCategoriesCollection;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByStores\GrossSalesByStoresCollection;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesReportManager;
use Lighthouse\CoreBundle\Document\Report\Store\StoreGrossSalesReportByHours;
use Lighthouse\CoreBundle\Document\Report\Store\StoreGrossSalesReportCollection;
use Lighthouse\CoreBundle\Document\Report\Store\StoreGrossSalesReportNow;
use Lighthouse\CoreBundle\Document\Report\Store\StoreGrossSalesRepository;
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

class ReportController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.store_gross_sales")
     * @var StoreGrossSalesRepository
     */
    protected $storeGrossSalesRepository;

    /**
     * @DI\Inject("lighthouse.core.document.report.gross_sales.manager")
     * @var GrossSalesReportManager
     */
    protected $grossSalesReportManager;

    /**
     * @param Store $store
     * @param Request $request
     * @return Cursor
     *
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER")
     * @Rest\Route("stores/{store}/reports/grossSales")
     * @ApiDoc
     */
    public function getStoreReportsGrossSalesAction(Store $store, Request $request)
    {
        $time = $request->get('time', 'now');
        $result = $this->storeGrossSalesRepository->findByStoreAndDate($store, $time);
        $collection = new StoreGrossSalesReportCollection($result);
        $dates = $this->storeGrossSalesRepository->getDatesForFullDayReport($time);
        $ids = $this->storeGrossSalesRepository->getIdsByStoreAndDateArray($store, $dates);
        $response =  new StoreGrossSalesReportNow($collection, $dates, $ids);
        return $response;
    }

    /**
     * @param Store $store
     * @param Request $request
     * @return StoreGrossSalesReportByHours
     *
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER")
     * @Rest\Route("stores/{store}/reports/grossSalesByHours")
     * @ApiDoc
     */
    public function getStoreReportsGrossSalesByHoursAction(Store $store, Request $request)
    {
        $time = $request->get('time', 'now');
        $todayReports = $this->storeGrossSalesRepository->findByStoreAndDateLimitDayHour($store, $time);
        $yesterdayReports = $this->storeGrossSalesRepository->findByStoreAndDateLimitDayHour($store, $time . " -1 day");
        $weekAgoReports = $this->storeGrossSalesRepository->findByStoreAndDateLimitDayHour($store, $time . " -1 week");

        $return = new StoreGrossSalesReportByHours($todayReports, $yesterdayReports, $weekAgoReports, $time);
        return $return;
    }

    /**
     * @param Request $request
     * @return GrossSalesByStoresCollection
     *
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @Rest\Route("reports/grossSalesByStores")
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
     * @ApiDoc
     */
    public function getReportsGrossSalesByGroupsAction(Store $store, Request $request)
    {
        $time = new DateTime($request->get('time', 'now'));
        return $this->grossSalesReportManager->getGrossSalesByGroups($store, $time);
    }
}
