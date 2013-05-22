<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\LoggableCursor;
use FOS\Rest\Util\Codes;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\ProductCollection;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.product")
     * @var ProductRepository
     */
    protected $documentRepository;

    /**
     * @return AbstractType
     */
    protected function getDocumentFormType()
    {
        return new ProductType();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\Product\Product
     *
     * @Rest\View(statusCode=201)
     */
    public function postProductsAction(Request $request)
    {
        return $this->processPost($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $id
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\Product\Product
     *
     * @Rest\View(statusCode=204)
     */
    public function putProductsAction(Request $request, $id)
    {
        return $this->processPut($request, $id);
    }

    /**
     * @param string $id
     * @return \Lighthouse\CoreBundle\Document\Product\Product
     */
    public function getProductAction($id)
    {
        return $this->processGet($id);
    }

    /**
     * @param string $property
     * @return \Lighthouse\CoreBundle\Document\Product\ProductCollection
     */
    public function getProductsSearchAction($property)
    {
        /* @var LoggableCursor $cursor */

        switch ($property) {
            case 'name':
            case 'sku':
            case 'barcode':
                $query = $this->getRequest()->get('query');
                $cursor = $this->getDocumentRepository()
                    ->searchEntry($property, $query);
                break;
            default:
                $cursor = array();
        }

        return new ProductCollection($cursor);
    }

    /**
     * @return \Lighthouse\CoreBundle\Document\Product\ProductCollection
     */
    public function getProductsAction()
    {
        /* @var LoggableCursor $cursor */
        $cursor = $this->getDocumentRepository()->findAll();
        $collection = new ProductCollection($cursor);
        return $collection;
    }
}
