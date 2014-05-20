<?php

namespace Lighthouse\ReportsBundle\Controller;

use DateTime;
use Doctrine\ODM\MongoDB\Cursor;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\ReportsBundle\Document\GrossMargin\Store\DayGrossMarginCollection;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @param Request $request
     * @return DayGrossMarginCollection
     *
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @Rest\Route("reports/grossMargin")
     * @ApiDoc
     */
    public function getReportsGrossMarginAction(Request $request)
    {
        $time = new DateTime($request->get('time', 'now'));
        return $this->grossMarginManager->getGrossMarginReport($time);
    }
}
