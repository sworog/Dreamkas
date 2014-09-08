<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovement;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementFilter;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementRepository;
use Lighthouse\CoreBundle\Form\StockMovement\StockMovementFilterType;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class StockMovementController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.stock_movement")
     * @var StockMovementRepository
     */
    protected $documentRepository;

    /**
     * @return StockMovementFilterType
     */
    protected function getDocumentFormType()
    {
        return new StockMovementFilterType();
    }

    /**
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(resource=true)
     * @Rest\Route("/stockMovements")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     * @return Cursor|StockMovement[]|FormInterface
     */
    public function getStockMovementsAction(Request $request)
    {
        $filter = new StockMovementFilter();
        $form = $this->submitForm($request, $filter);
        if (!$form->isValid()) {
            return $form;
        } else {
            return $this->documentRepository->findByFilter($filter);
        }
    }
}
