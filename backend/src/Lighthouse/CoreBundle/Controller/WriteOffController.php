<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\WriteOff\WriteOff;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOffCollection;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOffRepository;
use Lighthouse\CoreBundle\Form\WriteOffType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

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
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function postWriteoffsAction(Request $request)
    {
        return $this->processPost($request);
    }

    /**
     * @Rest\View(statusCode=200)
     *
     * @param Request $request
     * @param WriteOff $writeOff
     * @return \FOS\RestBundle\View\View|WriteOff
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function putWriteoffsAction(Request $request, WriteOff $writeOff)
    {
        return $this->processForm($request, $writeOff);
    }

    /**
     * @param WriteOff $writeOff
     * @return \Lighthouse\CoreBundle\Document\WriteOff\WriteOff
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getWriteoffAction(WriteOff $writeOff)
    {
        return $writeOff;
    }

    /**
     * @return WriteOffCollection
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc(
     *      resource=true
     * )
     */
    public function getWriteoffsAction()
    {
        $cursor = $this->documentRepository->findAll();
        $collection = new WriteOffCollection($cursor);
        return $collection;
    }
}
