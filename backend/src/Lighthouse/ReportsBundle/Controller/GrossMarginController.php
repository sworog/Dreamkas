<?php

namespace Lighthouse\ReportsBundle\Controller;

use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;
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

class GrossMarginController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.reports.gross_margin.manager")
     * @var GrossMarginManager
     */
    protected $grossMarginManager;

    /**
     * @param Store $store
     * @param Request $request
     * @return Cursor
     *
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER")
     * @Rest\Route("stores/{store}/reports/grossMargin")
     * @ApiDoc
     */
    public function getStoreReportsGrossMarginAction(Store $store, Request $request)
    {
        $time = new DateTime($request->get('time', 'now'));
        return $this->grossMarginManager->getStoreGrossMarginReport($store, $time);
    }
}
