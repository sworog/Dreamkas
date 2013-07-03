<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Lighthouse\CoreBundle\Security\PermissionExtractor;
use Lighthouse\CoreBundle\Service\AveragePriceService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Serializer\Serializer;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ServiceController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.service.average_price")
     * @var AveragePriceService
     */
    protected $averagePriceService;

    /**
     * @DI\Inject("serializer")
     * @var Serializer
     */
    protected $serializer;

    /**
     * @DI\Inject("lighthouse.core.security.permissions_extractor")
     * @var PermissionExtractor
     */
    protected $permissionExtractor;

    /**
     * @Route("service/recalculate-average-purchase-price")
     * @Rest\View(statusCode=200)
     * @ApiDoc
     */
    public function recalculateAveragePurchasePriceAction()
    {
        $this->averagePriceService->recalculateAveragePrice();
        return array('ok' => true);
    }

    /**
     * @Route("service/permissions")
     * @Rest\View(statusCode=200)
     * @ApiDoc
     */
    public function permissionsAction()
    {
        $permissions = $this->permissionExtractor->extractAll();
        return new JsonResponse($permissions);
    }
}
