<?php

namespace Lighthouse\CoreBundle\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use Lighthouse\CoreBundle\Service\AveragePriceService;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;

class ServiceController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.service.average_price")
     * @var AveragePriceService
     */
    protected $averagePriceService;

    /**
     * @Route("service/recalculate-average-purchase-price")
     * @Rest\View(statusCode=200)
     */
    public function recalculateAveragePurchasePriceAction()
    {
        $this->averagePriceService->recalculateAveragePrice();
        return array('ok' => true);
    }
}