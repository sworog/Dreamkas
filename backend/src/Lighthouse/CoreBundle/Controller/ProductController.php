<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Product;
use Lighthouse\CoreBundle\Form\ProductType;
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

    public function postProductAction()
    {
        $product = new Product();
        $productType = new ProductType($this->odm);

        $form = $this->createForm($productType, $product);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $data = $form->getData();
            $this->odm->getManager()->persist($product);
            $this->odm->getManager()->flush();
        }

        $errors = $form->getErrorsAsString();

        return $product;
    }

    public function getProductAction($id)
    {
        $product = $this->getProductRepository()->find($id);
        return $product;
    }

    public function getProductsAction()
    {
        $products = $this->getProductRepository()->findAll();
        return $products;
    }
}
