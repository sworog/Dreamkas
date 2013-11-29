<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use FOS\RestBundle\Controller\FOSRestController;
use Lighthouse\CoreBundle\Document\Report\Store\StoreGrossSalesReportCollection;
use Lighthouse\CoreBundle\Document\Report\Store\StoreGrossSalesReportNow;
use Lighthouse\CoreBundle\Document\Report\Store\StoreGrossSalesRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use JMS\DiExtraBundle\Annotation as DI;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Symfony\Component\HttpFoundation\Request;

class ReportController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.store_gross_sales")
     * @var StoreGrossSalesRepository
     */
    protected $storeGrossSalesRepository;

    /**
     * @param Store $store
     * @param Request $request
     * @return Cursor
     * @ApiDoc
     */
    public function getStoreReportGrosssalesAction(Store $store, Request $request)
    {
        $time = $request->get('time', 'now');
        $result = $this->storeGrossSalesRepository->findByStoreAndDate($store, $time);
        $collection = new StoreGrossSalesReportCollection($result);
        $dates = $this->storeGrossSalesRepository->getDatesForFullDayReport($time);
        $ids = $this->storeGrossSalesRepository->getIdsByStoreAndDateArray($store, $dates);
        $response =  new StoreGrossSalesReportNow($collection, $dates, $ids);
        return $response;
    }
}
