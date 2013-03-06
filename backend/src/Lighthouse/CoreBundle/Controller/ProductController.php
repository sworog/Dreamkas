<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\DataMapper\ProductMapper;
use Lighthouse\CoreBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @DI\Inject("lighthouse_core.mapper.product")
     * @var ProductMapper
     */
    protected $productMapper;

    /**
     * @Route("/api/1/product", name="product_create", requirements={"_method"="POST"})
     */
    public function createAction()
    {
        $data = $this->getRequest()->request->getIterator()->getArrayCopy();
        $product = new Product();
        $product->populate($data);

        if ($this->productMapper->create($product)) {
            return new Response('', 201);
        }

        return new Response('', 400);
    }

    /**
     * @Route("/api/1/product", name="product_index", requirements={"_method"="GET"})
     * @Rest\View
     */
    public function indexAction()
    {
        $product = new Product();
        $product->name = "Кефир";
        return $product;
    }
}
