<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Lighthouse\CoreBundle\Document\Supplier\SupplierRepository;
use Lighthouse\CoreBundle\Form\SupplierType;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;

class SupplierController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.supplier")
     * @var SupplierRepository
     */
    protected $documentRepository;

    /**
     * @return SupplierType
     */
    protected function getDocumentFormType()
    {
        return new SupplierType();
    }

    /**
     * @param Request $request
     * @return View|\Lighthouse\CoreBundle\Document\AbstractDocument
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(
     *      resource=true
     * )
     */
    public function postSuppliersAction(Request $request)
    {
        return $this->processPost($request);
    }
}
