<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockIn;
use Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockInRepository;
use Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockInHighlightGenerator;
use Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockInFilter;
use Lighthouse\CoreBundle\Form\StockMovement\StockInType;
use Lighthouse\CoreBundle\Meta\MetaCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StockInController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.stock_movement.stockin")
     * @var StockInRepository
     */
    protected $documentRepository;

    /**
     * @return StockInType|FormInterface
     */
    protected function getDocumentFormType()
    {
        return new StockInType();
    }

    /**
     * @param Request $request
     * @return FormInterface|StockIn
     *
     * @Rest\Route("stockIns")
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function postStockInsAction(Request $request)
    {
        $stockIn = $this->documentRepository->createNew();
        $formType = new StockInType(true);
        return $this->processForm($request, $stockIn, $formType);
    }

    /**
     * @param Store $store
     * @param Request $request
     * @return FormInterface|StockIn
     *
     * @Rest\Route("stores/{store}/stockIns")
     * @Rest\View(statusCode=201)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function postStoreStockInsAction(Store $store, Request $request)
    {
        $stockIn = $this->documentRepository->createNew();
        $stockIn->store = $store;
        return $this->processForm($request, $stockIn);
    }

    /**
     * @param StockIn $stockIn
     * @param Request $request
     * @return FormInterface|StockIn
     *
     * @Rest\Route("stockIns/{stockIn}")
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function putStockInsAction(StockIn $stockIn, Request $request)
    {
        $formType = new StockInType(true);
        $this->documentRepository->resetProducts($stockIn);
        return $this->processForm($request, $stockIn, $formType);
    }

    /**
     * @param Store $store
     * @param StockIn $stockIn
     * @param Request $request
     * @return FormInterface|StockIn
     *
     * @Rest\Route("stores/{store}/stockIns/{stockIn}")
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function putStoreStockInsAction(Store $store, StockIn $stockIn, Request $request)
    {
        $this->checkStockInStore($store, $stockIn);
        $this->documentRepository->resetProducts($stockIn);
        return $this->processForm($request, $stockIn);
    }

    /**
     * @param StockIn $stockIn
     *
     * @Rest\Route("stockIns/{stockIn}")
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function deleteStockInsAction(StockIn $stockIn)
    {
        $this->processDelete($stockIn);
    }

    /**
     * @param StockIn $stockIn
     * @return StockIn
     *
     * @Rest\Route("stockIns/{stockIn}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function getStockInAction(StockIn $stockIn)
    {
        return $stockIn;
    }

    /**
     * @param Store $store
     * @param StockIn $stockIn
     * @return StockIn
     *
     * @Rest\Route("stores/{store}/stockIns/{stockIn}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getStoreStockInAction(Store $store, StockIn $stockIn)
    {
        $this->checkStockInStore($store, $stockIn);
        return $stockIn;
    }

    /**
     * @param Store $store
     * @param StockInFilter $filter
     * @return MetaCollection|StockIn[]|Cursor
     *
     * @Rest\Route("stores/{store}/stockIns")
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function getStoreStockInsAction(Store $store, StockInFilter $filter)
    {
        $stockIns = $this->documentRepository->findByStore($store->id, $filter);
        if ($filter->hasNumber()) {
            $highlightGenerator = new StockInHighlightGenerator($filter);
            $collection = new MetaCollection($stockIns);
            $collection->addMetaGenerator($highlightGenerator);
        } else {
            $collection = $stockIns;
        }
        return $collection;
    }

    /**
     * @param Store $store
     * @param StockIn $stockin
     * @throws NotFoundHttpException
     */
    protected function checkStockInStore(Store $store, StockIn $stockin)
    {
        if ($stockin->store !== $store) {
            throw new NotFoundHttpException(sprintf("%s object not found", get_class($stockin)));
        }
    }
}
