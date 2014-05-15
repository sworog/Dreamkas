<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\LoggableCursor;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\ProductCollection;
use Lighthouse\CoreBundle\Document\Product\ProductFilter;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Exception\FlushFailedException;
use Lighthouse\CoreBundle\Form\ProductType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use MongoDuplicateKeyException;

class ProductController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.product")
     * @var ProductRepository
     */
    protected $documentRepository;

    /**
     * @return ProductType
     */
    protected function getDocumentFormType()
    {
        return new ProductType();
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
     * @return View|Product
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
     * @return View|Product
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
     * @return Product
     * @ApiDoc
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER,ROLE_COMMERCIAL_MANAGER")
     */
    public function getProductAction(Product $product)
    {
        return $product;
    }

    /**
     * @param string $property
     * @param ProductFilter $filter
     * @return ProductCollection
     * @ApiDoc
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER,ROLE_COMMERCIAL_MANAGER")
     * @Rest\View(serializerGroups={"Collection"})
     * @Rest\Route("products/{property}/search")
     */
    public function getProductsSearchAction($property, ProductFilter $filter)
    {
        $filter->setProperties($property);
        $cursor = $this->documentRepository->search($filter);
        return new ProductCollection($cursor);
    }

    /**
     * @return ProductCollection
     * @ApiDoc(
     *      resource=true
     * )
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER,ROLE_DEPARTMENT_MANAGER")
     */
    public function getProductsAction()
    {
        /* @var LoggableCursor $cursor */
        $cursor = $this->documentRepository->findAll();
        return new ProductCollection($cursor);
    }

    /**
     * @param SubCategory $subCategory
     * @return ProductCollection
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER,ROLE_DEPARTMENT_MANAGER")
     * @Rest\View(serializerGroups={"Collection"})
     * @ApiDoc
     */
    public function getSubcategoryProductsAction(SubCategory $subCategory)
    {
        $cursor = $this->documentRepository->findBySubCategory($subCategory);
        return new ProductCollection($cursor);
    }
}
