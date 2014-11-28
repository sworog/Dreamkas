<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\ProductFilter;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Exception\FlushFailedException;
use Lighthouse\CoreBundle\Form\LimitType;
use Lighthouse\CoreBundle\Form\Product\ProductType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use MongoDuplicateKeyException;
use stdClass;

class ProductController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.product")
     * @var ProductRepository
     */
    protected $documentRepository;

    /**
     * @DI\Inject("lighthouse.core.form.product_type");
     * @var ProductType
     */
    protected $productType;

    /**
     * @return ProductType
     */
    protected function getDocumentFormType()
    {
        return $this->productType;
    }

    /**
     * @param FlushFailedException $e
     * @return FormInterface
     */
    protected function handleFlushFailedException(FlushFailedException $e)
    {
        if ($e->getCause() instanceof MongoDuplicateKeyException) {
            return $this->addFormError($e->getForm(), null, 'lighthouse.validation.errors.product.sku.unique');
        } else {
            return parent::handleFlushFailedException($e);
        }
    }

    /**
     * @param Request $request
     * @return FormInterface|Product
     *
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function postProductsAction(Request $request)
    {
        return $this->processPost($request);
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return FormInterface|Product
     *
     * @Rest\View(statusCode=200)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function putProductsAction(Request $request, Product $product)
    {
        return $this->processForm($request, $product);
    }


    /**
     * @param Product $product
     *
     * @Rest\View(statusCode=204)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function deleteProductsAction(Product $product)
    {
        $this->processDelete($product);
    }

    /**
     * @param ProductFilter $filter
     * @return Product[]|Cursor
     *
     * @Rest\View(serializerGroups={"Collection"})
     * @Rest\Route("products/search")
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER,ROLE_STORE_MANAGER,ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getProductsSearchAction(ProductFilter $filter)
    {
        return $this->documentRepository->search($filter);
    }

    /**
     * @param Product $product
     * @return Product
     * @ApiDoc
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER,ROLE_STORE_MANAGER,ROLE_DEPARTMENT_MANAGER")
     */
    public function getProductAction(Product $product)
    {
        return $product;
    }

    /**
     * @param Request $request
     * @return Product[]|Cursor
     * @ApiDoc(resource=true)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER,ROLE_STORE_MANAGER,ROLE_DEPARTMENT_MANAGER")
     */
    public function getProductsAction(Request $request)
    {
        $filter = new stdClass();
        $filter->limit = null;

        $documentRepository = $this->documentRepository;
        return $this->processFormCallback(
            $request,
            function ($filter) use ($documentRepository) {
                return $documentRepository->findAllActive()->limit($filter->limit);
            },
            $filter,
            new LimitType()
        );
    }

    /**
     * @param SubCategory $subCategory
     * @return Product[]|Cursor
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER,ROLE_STORE_MANAGER,ROLE_DEPARTMENT_MANAGER")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc
     */
    public function getSubcategoryProductsAction(SubCategory $subCategory)
    {
        return $this->documentRepository->findBySubCategory($subCategory);
    }
}
