<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\ProductFilter;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductCollection;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Form\StoreProductType;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

class StoreProductController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.store_product")
     * @var StoreProductRepository
     */
    protected $documentRepository;

    /**
     * @return StoreProductType
     */
    protected function getDocumentFormType()
    {
        return new StoreProductType();
    }

    /**
     * @param Store $store
     * @param ProductFilter $filter
     * @return StoreProductCollection
     *
     * @Rest\Route("stores/{store}/products")
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER,ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getStoreProductsAction(Store $store, ProductFilter $filter)
    {
        $filter->setPropertiesRequired(false);
        return $this->documentRepository->search($store, $filter);
    }

    /**
     * @param Store $store
     * @param Product $product
     * @return StoreProduct
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER,ACL_DEPARTMENT_MANAGER")
     * @ApiDoc(
     *      resource=true
     * )
     */
    public function getStoreProductAction(Store $store, Product $product)
    {
        return $this->findStoreProduct($store, $product);
    }

    /**
     * @param Store $store
     * @param Product $product
     * @param Request $request
     * @return FormInterface|StoreProduct
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER")
     * @ApiDoc
     */
    public function putStoreProductAction(Store $store, Product $product, Request $request)
    {
        $storeProduct = $this->findStoreProduct($store, $product);
        return $this->processForm($request, $storeProduct);
    }

    /**
     * @param Store $store
     * @param ProductFilter $filter
     * @return StoreProductCollection
     * @ApiDoc
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @Rest\View(serializerGroups={"Collection"})
     * @Rest\Route("stores/{store}/search/products")
     */
    public function getStoreSearchProductsAction(Store $store, ProductFilter $filter)
    {
        return $this->documentRepository->search($store, $filter);
    }


    /**
     * @param Store $store
     * @param SubCategory $subCategory
     * @return StoreProductCollection
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER,ACL_DEPARTMENT_MANAGER")
     * @Rest\View(serializerGroups={"Collection"})
     * @ApiDoc
     */
    public function getStoreSubcategoryProductsAction(Store $store, SubCategory $subCategory)
    {
        return $this->documentRepository->findByStoreSubCategory($store, $subCategory);
    }

    /**
     * @param Store $store
     * @param Product $product
     * @return StoreProduct
     */
    protected function findStoreProduct(Store $store, Product $product)
    {
        return $this->documentRepository->findOrCreateByStoreProduct($store, $product);
    }
}
