<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends FOSRestController
{
    /**
     * @DI\Inject("doctrine_mongodb")
     * @var ManagerRegistry
     */
    protected $odm;

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getProductRepository()
    {
        return $this->odm->getRepository("LighthouseCoreBundle:Product");
    }

    /**
     * @Route("/api/1/product", name="product_create", requirements={"_method"="POST"})
     * @Rest\View(statusCode=201)
     */
    public function createAction()
    {
        $data = $this->getRequest()->request->getIterator()->getArrayCopy();
        $product = new Product();
        $product->populate($data);

        $this->odm->getManager()->persist($product);
        $this->odm->getManager()->flush();

        return $product;
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
