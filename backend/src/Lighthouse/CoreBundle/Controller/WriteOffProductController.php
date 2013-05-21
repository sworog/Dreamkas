<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProduct;
use Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProductCollection;
use Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProductRepository;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOff;
use Lighthouse\CoreBundle\Form\WriteOffProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;

class WriteOffProductController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.writeoff.product")
     * @var WriteOffProductRepository
     */
    protected $documentRepository;

    /**
     * @DI\Inject("lighthouse.core.document.repository.writeoff")
     * @var WriteOffRepository
     */
    protected $writeOffRepository;

    /**
     * @return WriteOffProductType
     */
    protected function getDocumentFormType()
    {
        return new WriteOffProductType();
    }

    /**
     * @Rest\View(statusCode=201)
     * @param Request $request
     * @param string $writeOffId
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\AbstractDocument
     */
    public function postProductsAction(Request $request, $writeOffId)
    {
        $writeOff = $this->findWriteOff($writeOffId);
        $writeOffProduct = new WriteOffProduct();
        $writeOffProduct->writeOff = $writeOff;

        return $this->processForm($request, $writeOffProduct);
    }

    /**
     * @param Request $request
     * @param string $writeOffId
     * @param string $writeOffProductId
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\AbstractDocument
     */
    public function putProductsAction(Request $request, $writeOffId, $writeOffProductId)
    {
        $writeOffProduct = $this->findWriteOffProduct($writeOffProductId, $writeOffId);
        return $this->processForm($request, $writeOffProduct);
    }

    /**
     * @Rest\View(statusCode=204)
     * @param string $writeOffId
     * @param string $writeOffProductId
     */
    public function deleteProductsAction($writeOffId, $writeOffProductId)
    {
        $writeOffProduct = $this->findWriteOffProduct($writeOffProductId, $writeOffId);
        $this->getDocumentRepository()->getDocumentManager()->remove($writeOffProduct);
        $this->getDocumentRepository()->getDocumentManager()->flush();
    }

    /**
     * @param string $writeOffId
     * @param string $writeOffProductId
     * @return WriteOffProduct
     */
    public function getProductAction($writeOffId, $writeOffProductId)
    {
        return $this->findWriteOffProduct($writeOffProductId, $writeOffId);
    }

    /**
     * @param string $writeOffId
     * @return WriteOffProductCollection
     */
    public function getProductsAction($writeOffId)
    {
        $writeOff = $this->findWriteOff($writeOffId);
        return $this->getDocumentRepository()->findAllByWriteOff($writeOff);
    }

    /**
     * @param string $writeOffId
     * @return WriteOff
     * @throws NotFoundHttpException
     */
    protected function findWriteOff($writeOffId)
    {
        $writeOff = $this->writeOffRepository->find($writeOffId);
        if (null === $writeOff) {
            throw new NotFoundHttpException("WriteOff not found");
        }
        return $writeOff;
    }

    /**
     * @param string $writeOffProduct
     * @param string $writeOffId
     * @throws NotFoundHttpException
     * @return WriteOffProduct
     */
    protected function findWriteOffProduct($writeOffProductId, $writeOffId)
    {
        $writeOff = $this->findWriteOff($writeOffId);
        $writeOffProduct = $this->getDocumentRepository()->find($writeOffProductId);
        if (null === $writeOffProduct) {
            throw new NotFoundHttpException("WriteOffProduct not found");
        } elseif ($writeOffProduct->writeOff->id != $writeOff->id) {
            throw new NotFoundHttpException("WriteOffProduct not found");
        }
        return $writeOffProduct;
    }
}
