<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use FOS\RestBundle\Controller\Annotations\View;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Lighthouse\CoreBundle\Document\Supplier\SupplierRepository;
use Lighthouse\CoreBundle\Exception\FlushFailedException;
use Lighthouse\CoreBundle\Form\SupplierType;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;
use MongoDuplicateKeyException;

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
     * @param FlushFailedException $e
     * @return FormInterface
     */
    protected function handleFlushFailedException(FlushFailedException $e)
    {
        if ($e->getCause() instanceof MongoDuplicateKeyException) {
            return $this->addFormError($e->getForm(), 'name', 'lighthouse.validation.errors.supplier.name.unique');
        } else {
            return parent::handleFlushFailedException($e);
        }
    }

    /**
     * @param Request $request
     * @return View|Supplier
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

    /**
     * @param Request $request
     * @param Supplier $supplier
     * @return View|Supplier
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function putSupplierAction(Request $request, Supplier $supplier)
    {
        return $this->processForm($request, $supplier);
    }

    /**
     * @return Cursor|Supplier[]
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER,ROLE_DEPARTMENT_MANAGER,ROLE_STORE_MANAGER")
     * @ApiDoc
     */
    public function getSuppliersAction()
    {
        return $this->documentRepository->findAll();
    }

    /**
     * @param Supplier $supplier
     * @return Supplier
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER,ROLE_DEPARTMENT_MANAGER,ROLE_STORE_MANAGER")
     * @ApiDoc
     */
    public function getSupplierAction(Supplier $supplier)
    {
        return $supplier;
    }
}
