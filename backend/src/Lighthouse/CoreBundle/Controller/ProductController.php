<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\LoggableCursor;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Product;
use Lighthouse\CoreBundle\Document\ProductCollection;
use Lighthouse\CoreBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    protected function getProductRepository()
    {
        return $this->odm->getRepository("LighthouseCoreBundle:Product");
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Lighthouse\CoreBundle\Document\Product
     *
     * @Rest\View(statusCode=201)
     */
    public function postProductsAction(Request $request)
    {
        $product = new Product();
        $productType = new ProductType($this->odm);

        $form = $this->createForm($productType, $product);
        $form->bind($request);

        if ($form->isValid()) {
            $this->odm->getManager()->persist($product);
            $this->odm->getManager()->flush();
            return $product;
        }

        return View::create($form, 400);
    }

    public function getProductAction($id)
    {
        $product = $this->getProductRepository()->find($id);
        if (!$product instanceof Product) {
            throw new NotFoundHttpException('Product not found');
        }
        return $product;
    }

    public function getProductsAction()
    {
        /* @var LoggableCursor $cursor */
        $cursor = $this->getProductRepository()->findAll();
        $collection = new ProductCollection($cursor);
        return $collection;
    }
}
