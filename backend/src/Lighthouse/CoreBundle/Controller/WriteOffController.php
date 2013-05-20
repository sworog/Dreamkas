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

class WriteOffController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.writeoff")
     * @var WriteOffRepository
     */
    protected $writeOffRepository;

    /**
     * @Rest\View(statusCode=201)
     *
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function postWriteoffsAction(Request $request)
    {
        $writeOff = new WriteOff();
        return $this->processForm($request, $writeOff);
    }

    /**
     * @param Request $request
     * @param WriteOff $writeOff
     * @return \FOS\RestBundle\View\View
     */
    protected function processForm(Request $request, WriteOff $writeOff)
    {
        $form = $this->createForm(new WriteOffType(), $writeOff);
        $form->bind($request);

        if ($form->isValid()) {
            $this->writeOffRepository->getDocumentManager()->persist($writeOff);
            $this->writeOffRepository->getDocumentManager()->flush();
            return $writeOff;
        } else {
            return $this->view($form, Codes::HTTP_BAD_REQUEST);
        }
    }
}
