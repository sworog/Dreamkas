<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\Rest\Util\Codes;
use FOS\RestBundle\Controller\FOSRestController;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOff;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOffRepository;
use Lighthouse\CoreBundle\Form\WriteOffType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;

class WriteOffController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.writeoff")
     * @var WriteOffRepository
     */
    protected $writeOffRepository;

    /**
     * @return \Lighthouse\CoreBundle\Document\DocumentRepository|WriteOffRepository
     */
    protected function getDocumentRepository()
    {
        return $this->writeOffRepository;
    }

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
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function postWriteoffsAction(Request $request)
    {
        return $this->processPost($request);
    }

    /**
     * @Rest\View(statusCode=200)
     *
     * @param Request $request
     */
    public function putWriteoffsAction(Request $request, $id)
    {
        return $this->processPut($request, $id);
    }
}
