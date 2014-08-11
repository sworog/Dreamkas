<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOff;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOffRepository;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOffHighlightGenerator;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOffFilter;
use Lighthouse\CoreBundle\Form\WriteOffType;
use Lighthouse\CoreBundle\Meta\MetaCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WriteOffController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.stock_movement.writeoff")
     * @var WriteOffRepository
     */
    protected $documentRepository;

    /**
     * @return WriteOffType|FormInterface
     */
    protected function getDocumentFormType()
    {
        return new WriteOffType();
    }

    /**
     * @Rest\View(statusCode=201)
     *
     * @param Request $request
     * @return FormInterface|WriteOff
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function postWriteoffsAction(Request $request)
    {
        $writeOff = $this->documentRepository->createNew();
        $formType = new WriteOffType(true);
        return $this->processForm($request, $writeOff, $formType);
    }

    /**
     * @Rest\View(statusCode=201)
     *
     * @param Store $store
     * @param Request $request
     * @return FormInterface|WriteOff
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function postStoreWriteoffsAction(Store $store, Request $request)
    {
        $writeOff = $this->documentRepository->createNew();
        $writeOff->store = $store;
        return $this->processForm($request, $writeOff);
    }

    /**
     * @param WriteOff $writeOff
     * @param Request $request
     * @return FormInterface|WriteOff
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function putWriteoffsAction(WriteOff $writeOff, Request $request)
    {
        $formType = new WriteOffType(true);
        $this->documentRepository->resetProducts($writeOff);
        return $this->processForm($request, $writeOff, $formType);
    }

    /**
     * @param Store $store
     * @param WriteOff $writeOff
     * @param Request $request
     * @return FormInterface|WriteOff
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function putStoreWriteoffsAction(Store $store, WriteOff $writeOff, Request $request)
    {
        $this->checkWriteOffStore($store, $writeOff);
        $this->documentRepository->resetProducts($writeOff);
        return $this->processForm($request, $writeOff);
    }

    /**
     * @param WriteOff $writeOff
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function deleteWriteoffsAction(WriteOff $writeOff)
    {
        $this->processDelete($writeOff);
    }

    /**
     * @param WriteOff $writeOff
     * @return WriteOff
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc
     */
    public function getWriteoffAction(WriteOff $writeOff)
    {
        return $writeOff;
    }

    /**
     * @param Store $store
     * @param WriteOff $writeOff
     * @return WriteOff
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc
     */
    public function getStoreWriteoffAction(Store $store, WriteOff $writeOff)
    {
        $this->checkWriteOffStore($store, $writeOff);
        return $writeOff;
    }

    /**
     * @param Store $store
     * @param WriteOffFilter $filter
     * @return MetaCollection|WriteOff[]|Cursor
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc(resource=true)
     * @Rest\Route("stores/{store}/writeoffs")
     */
    public function getStoreWriteOffsAction(Store $store, WriteOffFilter $filter)
    {
        $writeOffs = $this->documentRepository->findByStore($store->id, $filter);
        if ($filter->hasNumber()) {
            $highlightGenerator = new WriteOffHighlightGenerator($filter);
            $collection = new MetaCollection($writeOffs);
            $collection->addMetaGenerator($highlightGenerator);
        } else {
            $collection = $writeOffs;
        }
        return $collection;
    }

    /**
     * @param Store $store
     * @param WriteOff $writeoff
     * @throws NotFoundHttpException
     */
    protected function checkWriteOffStore(Store $store, WriteOff $writeoff)
    {
        if ($writeoff->store !== $store) {
            throw new NotFoundHttpException(sprintf("%s object not found", get_class($writeoff)));
        }
    }
}
