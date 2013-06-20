<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\WriteOff\WriteOff;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOffCollection;
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
     * @param Request $request
     * @return \FOS\RestBundle\View\View|WriteOff
     */
    public function postWriteoffsAction(Request $request)
    {
        return $this->processPost($request);
    }

    /**
     * @Rest\View(statusCode=200)
     *
     * @param Request $request
     * @param string $id
     * @return \FOS\RestBundle\View\View|WriteOff
     */
    public function putWriteoffsAction(Request $request, $id)
    {
        return $this->processPut($request, $id);
    }

    /**
     * @param string $id
     * @return \Lighthouse\CoreBundle\Document\WriteOff\WriteOff
     */
    public function getWriteoffAction($id)
    {
        return $this->processGet($id);
    }

    /**
     * @return WriteOffCollection
     */
    public function getWriteoffsAction()
    {
        $cursor = $this->documentRepository->findAll();
        $collection = new WriteOffCollection($cursor);
        return $collection;
    }
}
