<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProduct;
use Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProductCollection;
use Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProductRepository;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOff;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOffRepository;
use Lighthouse\CoreBundle\Form\WriteOffProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class WriteOffProductController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.writeoff.product")
     * @var WriteOffProductRepository
     */
    protected $documentRepository;

    /**
     * @return WriteOffProductType
     */
    protected function getDocumentFormType()
    {
        return new WriteOffProductType();
    }

    /**
     * @param Store $store
     * @param Request $request
     * @param WriteOff $writeOff
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\AbstractDocument
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function postProductsAction(Store $store, WriteOff $writeOff, Request $request)
    {
        $this->checkWriteoffStore($store, $writeOff);
        $writeOffProduct = new WriteOffProduct();
        $writeOffProduct->writeOff = $writeOff;

        return $this->processForm($request, $writeOffProduct);
    }

    /**
     * @param Store $store
     * @param Request $request
     * @param WriteOff $writeOff
     * @param WriteOffProduct $writeOffProduct
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\AbstractDocument
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function putProductsAction(
        Store $store,
        Request $request,
        WriteOff $writeOff,
        WriteOffProduct $writeOffProduct
    ) {
        $this->checkWriteOffProduct($writeOff, $writeOffProduct, $store);
        return $this->processForm($request, $writeOffProduct);
    }

    /**
     * @param Store $store
     * @param WriteOff $writeOff
     * @param WriteOffProduct $writeOffProduct
     * @return null
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function deleteProductsAction(Store $store, WriteOff $writeOff, WriteOffProduct $writeOffProduct)
    {
        $this->checkWriteOffProduct($writeOff, $writeOffProduct, $store);
        return $this->processDelete($writeOffProduct);
    }

    /**
     * @param Store $store
     * @param WriteOff $writeOff
     * @param WriteOffProduct $writeOffProduct
     * @return WriteOffProduct
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getProductAction(Store $store, WriteOff $writeOff, WriteOffProduct $writeOffProduct)
    {
        $this->checkWriteOffProduct($writeOff, $writeOffProduct, $store);
        return $writeOffProduct;
    }

    /**
     * @param Store $store
     * @param WriteOff $writeOff
     * @return WriteOffProductCollection
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc(
     *      resource=true
     * )
     */
    public function getProductsAction(Store $store, WriteOff $writeOff)
    {
        $this->checkWriteoffStore($store, $writeOff);
        return $this->documentRepository->findAllByWriteOff($writeOff);
    }

    /**
     * @param Store $store
     * @param WriteOff $writeOff
     * @param WriteOffProduct $writeOffProduct
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function checkWriteOffProduct(WriteOff $writeOff, WriteOffProduct $writeOffProduct, Store $store)
    {
        $this->checkWriteoffStore($store, $writeOff);
        if ($writeOffProduct->writeOff->id != $writeOff->id) {
            throw new NotFoundHttpException(sprintf("%s object not found", get_class($writeOffProduct)));
        }
    }

    /**
     * @param Store $store
     * @param WriteOff $writeoff
     * @throws NotFoundHttpException
     */
    protected function checkWriteoffStore(Store $store, WriteOff $writeoff)
    {
        if ($writeoff->store !== $store) {
            throw new NotFoundHttpException(sprintf("%s object not found", get_class($writeoff)));
        }
    }
}
