<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProduct;
use Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProductCollection;
use Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProductRepository;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOff;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOffRepository;
use Lighthouse\CoreBundle\Form\WriteOffProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class WriteOffProductController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.writeoff.product")
     * @var WriteOffProductRepository
     */
    protected $documentRepository;

    /**
     * @return WriteOffProductType
     */
    protected function getDocumentFormType()
    {
        return new WriteOffProductType();
    }

    /**
     * @param Request $request
     * @param WriteOff $writeOff
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\AbstractDocument
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function postProductsAction(Request $request, WriteOff $writeOff)
    {
        $writeOffProduct = new WriteOffProduct();
        $writeOffProduct->writeOff = $writeOff;

        return $this->processForm($request, $writeOffProduct);
    }

    /**
     * @param Request $request
     * @param WriteOff $writeOffId
     * @param WriteOffProduct $writeOffProductId
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\AbstractDocument
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function putProductsAction(Request $request, WriteOff $writeOff, WriteOffProduct $writeOffProduct)
    {
        $this->checkWriteOffProduct($writeOff, $writeOffProduct);
        return $this->processForm($request, $writeOffProduct);
    }

    /**
     * @param WriteOff $writeOff
     * @param WriteOffProduct $writeOffProduct
     * @return null
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function deleteProductsAction(WriteOff $writeOff, WriteOffProduct $writeOffProduct)
    {
        $this->checkWriteOffProduct($writeOff, $writeOffProduct);
        return $this->processDelete($writeOffProduct);
    }

    /**
     * @param WriteOff $writeOff
     * @param WriteOffProduct $writeOffProduct
     * @return WriteOffProduct
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getProductAction(WriteOff $writeOff, WriteOffProduct $writeOffProduct)
    {
        $this->checkWriteOffProduct($writeOff, $writeOffProduct);
        return $writeOffProduct;
    }

    /**
     * @param WriteOff $writeOff
     * @return WriteOffProductCollection
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc(
     *      resource=true
     * )
     */
    public function getProductsAction(WriteOff $writeOff)
    {
        return $this->getDocumentRepository()->findAllByWriteOff($writeOff);
    }

    /**
     * @param WriteOff $writeOff
     * @param WriteOffProduct $writeOffProduct
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function checkWriteOffProduct(WriteOff $writeOff, WriteOffProduct $writeOffProduct)
    {
        if ($writeOffProduct->writeOff->id != $writeOff->id) {
            throw new NotFoundHttpException(sprintf("%s object not found", get_class($writeOffProduct)));
        }
    }
}
