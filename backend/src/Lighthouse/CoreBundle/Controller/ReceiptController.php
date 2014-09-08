<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\StockMovement\Receipt;
use Lighthouse\CoreBundle\Document\StockMovement\ReceiptRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Form\StockMovement\ReceiptType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReceiptController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.receipt")
     * @var ReceiptRepository
     */
    protected $documentRepository;

    /**
     * @return FormTypeInterface|null
     */
    protected function getDocumentFormType()
    {
        return null;
    }

    /**
     * @param Request $request
     * @param Store $store
     * @param string $type
     * @return FormInterface|Receipt
     *
     * @Rest\Route("stores/{store}/sales", defaults={"type"="Sale"}, name="receipt.sales.post")
     * @Rest\Route("stores/{store}/returns", defaults={"type"="Return"}, name="receipt.returns.post")
     * @Rest\View(statusCode=201)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function postStoreAction(Request $request, Store $store, $type)
    {
        $receipt = $this->documentRepository->createNewByType($type);
        $receipt->store = $store;
        $receiptType = new ReceiptType($type);
        return $this->processForm($request, $receipt, $receiptType);
    }

    /**
     * @param Store $store
     * @param Receipt $receipt
     * @param string $type
     * @return Receipt
     *
     * @Rest\Route("stores/{store}/sales/{id}", defaults={"type"="Sale"}, name="receipt.sales.get")
     * @Rest\Route("stores/{store}/returns/{id}", defaults={"type"="Return"}, name="receipt.returns.get")
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     *
     * @ParamConverter("receipt", options={"mapping": {"store": "store", "id": "id"}})
     */
    public function getStoreAction(Store $store, Receipt $receipt, $type)
    {
        if ($type === $receipt->getType()) {
            return $receipt;
        } else {
            throw new NotFoundHttpException(sprintf('%s object not found.', $type));
        }
    }
}
