<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use FOS\RestBundle\Controller\FOSRestController;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovement;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementFilter;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementRepository;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

class StockMovementController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.stock_movement")
     * @var StockMovementRepository
     */
    protected $documentRepository;

    /**
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(resource=true)
     * @Rest\Route("/stockMovements")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @param StockMovementFilter $filter
     * @return Cursor|StockMovement[]
     */
    public function getStockMovementsAction(StockMovementFilter $filter)
    {
        return $this->documentRepository->findByFilter($filter);
    }
}
