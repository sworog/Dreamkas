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
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
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
     * @return WriteOffType|\Symfony\Component\Form\AbstractType
     */
    protected function getDocumentFormType()
    {
        return new WriteOffType();
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
    public function postWriteoffsAction(Store $store, Request $request)
    {
        $writeOff = new WriteOff;
        $writeOff->store = $store;
        return $this->processForm($request, $writeOff);
    }

    /**
     * @Rest\View(statusCode=200)
     *
     * @param Store $store
     * @param WriteOff $writeOff
     * @param Request $request
     * @return FormInterface|WriteOff
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function putWriteoffsAction(Store $store, WriteOff $writeOff, Request $request)
    {
        $this->checkWriteOffStore($store, $writeOff);
        return $this->processForm($request, $writeOff);
    }

    /**
     * @param Store $store
     * @param WriteOff $writeOff
     * @return WriteOff
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getWriteoffAction(Store $store, WriteOff $writeOff)
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
    public function getWriteOffsAction(Store $store, WriteOffFilter $filter)
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
