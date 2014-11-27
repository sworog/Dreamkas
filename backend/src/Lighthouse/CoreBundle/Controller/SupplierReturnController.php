<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturn;
use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturnRepository;
use Lighthouse\CoreBundle\Form\SupplierReturnType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SupplierReturnController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.stock_movement.supplier_return")
     * @var SupplierReturnRepository
     */
    protected $documentRepository;

    /**
     * @DI\Inject("validator")
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @return SupplierReturnType|FormInterface
     */
    protected function getDocumentFormType()
    {
        return new SupplierReturnType();
    }

    /**
     * @param Request $request
     * @return FormInterface|SupplierReturn
     *
     * @Rest\Route("supplierReturns")
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function postSupplierReturnsAction(Request $request)
    {
        $supplierReturn = $this->documentRepository->createNew();
        $formType = new SupplierReturnType(true);
        return $this->processForm($request, $supplierReturn, $formType);
    }

    /**
     * @param Store $store
     * @param Request $request
     * @return FormInterface|SupplierReturn
     *
     * @Rest\Route("stores/{store}/supplierReturns")
     * @Rest\View(statusCode=201)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function postStoreSupplierReturnsAction(Store $store, Request $request)
    {
        $supplierReturn = $this->documentRepository->createNew();
        $supplierReturn->store = $store;
        return $this->processForm($request, $supplierReturn);
    }

    /**
     * @param SupplierReturn $supplierReturn
     * @param Request $request
     * @return FormInterface|SupplierReturn
     *
     * @Rest\Route("supplierReturns/{supplierReturn}")
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function putSupplierReturnsAction(SupplierReturn $supplierReturn, Request $request)
    {
        $preViolations = $this->validator->validate($supplierReturn, null, array('NotDeleted'));

        $formType = new SupplierReturnType(true);
        $this->documentRepository->resetProducts($supplierReturn);
        return $this->processForm($request, $supplierReturn, $formType, true, true, $preViolations);
    }

    /**
     * @param Store $store
     * @param SupplierReturn $supplierReturn
     * @param Request $request
     * @return FormInterface|SupplierReturn
     *
     * @Rest\Route("stores/{store}/supplierReturns/{supplierReturn}")
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function putStoreSupplierReturnsAction(Store $store, SupplierReturn $supplierReturn, Request $request)
    {
        $preViolations = $this->validator->validate($supplierReturn, null, array('NotDeleted'));

        $this->checkSupplierReturnStore($store, $supplierReturn);
        $this->documentRepository->resetProducts($supplierReturn);
        return $this->processForm($request, $supplierReturn, null, true, true, $preViolations);
    }

    /**
     * @param SupplierReturn $supplierReturn
     *
     * @Rest\Route("supplierReturns/{supplierReturn}")
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function deleteSupplierReturnsAction(SupplierReturn $supplierReturn)
    {
        $this->processDelete($supplierReturn);
    }

    /**
     * @param SupplierReturn $supplierReturn
     * @return SupplierReturn
     *
     * @Rest\Route("supplierReturns/{supplierReturn}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function getSupplierReturnAction(SupplierReturn $supplierReturn)
    {
        return $supplierReturn;
    }

    /**
     * @param Store $store
     * @param SupplierReturn $supplierReturn
     * @return SupplierReturn
     *
     * @Rest\Route("stores/{store}/supplierReturns/{supplierReturn}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getStoreSupplierReturnAction(Store $store, SupplierReturn $supplierReturn)
    {
        $this->checkSupplierReturnStore($store, $supplierReturn);
        return $supplierReturn;
    }

    /**
     * @param Store $store
     * @param SupplierReturn $supplierReturn
     * @throws NotFoundHttpException
     */
    protected function checkSupplierReturnStore(Store $store, SupplierReturn $supplierReturn)
    {
        if ($supplierReturn->store !== $store) {
            throw new NotFoundHttpException(sprintf("%s object not found", get_class($supplierReturn)));
        }
    }
}
