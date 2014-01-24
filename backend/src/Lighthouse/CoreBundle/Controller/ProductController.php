<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\LoggableCursor;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\ProductCollection;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

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
     * @param Request $request
     * @param string $property name, sku, barcode
     * @return ProductCollection
     * @ApiDoc
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER,ROLE_COMMERCIAL_MANAGER")
     * @Rest\View(serializerGroups={"Collection"})
     */
    public function getProductsSearchAction(Request $request, $property)
    {
        /* @var LoggableCursor $cursor */

        switch ($property) {
            case 'name':
            case 'sku':
            case 'barcode':
                $query = $request->get('query');
                $cursor = $this->documentRepository->searchEntry($property, $query);
                break;
            default:
                $cursor = array();
        }

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
        $cursor = $this->getDocumentRepository()->findAll();
        $collection = new ProductCollection($cursor);
        return $collection;
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
