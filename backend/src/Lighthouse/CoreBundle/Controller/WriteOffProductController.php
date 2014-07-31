<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\Product\WriteOffProduct;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\Product\WriteOffProductRepository;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOff;
use Lighthouse\CoreBundle\Form\WriteOffProductType;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class WriteOffProductController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.stock_movement.writeoff_product")
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
     * @return FormInterface|WriteOffProduct
     * @Rest\View(statusCode=201)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
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
     * @return FormInterface|WriteOffProduct
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
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
     * @return void
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function deleteProductsAction(Store $store, WriteOff $writeOff, WriteOffProduct $writeOffProduct)
    {
        $this->checkWriteOffProduct($writeOff, $writeOffProduct, $store);
        $this->processDelete($writeOffProduct);
    }

    /**
     * @param Store $store
     * @param WriteOff $writeOff
     * @param WriteOffProduct $writeOffProduct
     * @return WriteOffProduct
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
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
     * @return WriteOffProduct[]|Cursor
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc(
     *      resource=true
     * )
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     */
    public function getProductsAction(Store $store, WriteOff $writeOff)
    {
        $this->checkWriteoffStore($store, $writeOff);
        return $writeOff->products;
    }

    /**
     * @param Store $store
     * @param Product $product
     * @return WriteOffProduct[]|Cursor
     * @Rest\Route("stores/{store}/products/{product}/writeOffProducts")
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getProductWriteOffProductsAction(Store $store, Product $product)
    {
        return $this->documentRepository->findByStoreAndProduct($store->id, $product->id);
    }

    /**
     * @param Store $store
     * @param WriteOff $writeOff
     * @param WriteOffProduct $writeOffProduct
     * @throws NotFoundHttpException
     */
    protected function checkWriteOffProduct(WriteOff $writeOff, WriteOffProduct $writeOffProduct, Store $store)
    {
        $this->checkWriteoffStore($store, $writeOff);
        if ($writeOffProduct->writeOff !== $writeOff) {
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
