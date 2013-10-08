<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Document\Sale\Sale;
use Lighthouse\CoreBundle\Document\Sale\SaleRepository;
use Lighthouse\CoreBundle\Form\SaleType;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class SaleController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.sale")
     * @var SaleRepository
     */
    protected $documentRepository;

    /**
     * @return SaleType
     */
    protected function getDocumentFormType()
    {
        return new SaleType();
    }

    /**
     * @Rest\View(statusCode=201)
     *
     * @param Request $request
     * @return View|Sale
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc(
     *      resource=true
     * )
     */
    public function postSalesAction(Request $request)
    {
        return $this->processPost($request);
    }
}
