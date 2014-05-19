<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Lighthouse\CoreBundle\Document\Product\Barcode\Barcode;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Form\Product\Barcode\BarcodesType;

class ProductBarcodesController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.product")
     * @var ProductRepository
     */
    protected $documentRepository;

    /**
     * @return BarcodesType
     */
    protected function getDocumentFormType()
    {
        return new BarcodesType();
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return Form|Barcode[]
     *
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function putBarcodesAction(Request $request, Product $product)
    {
        $result = $this->processForm($request, $product);

        if ($result instanceof Product) {
            return $product->barcodes;
        } else {
            return $result;
        }
    }
}
