<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\Rest\Util\Codes;
use FOS\RestBundle\Controller\FOSRestController;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractRestController extends FOSRestController
{
    /**
     * @var DocumentRepository
     */
    protected $documentRepository;

    /**
     * @return DocumentRepository
     */
    protected function getDocumentRepository()
    {
        return $this->documentRepository;
    }

    /**
     * @return AbstractType
     */
    abstract protected function getDocumentFormType();

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param AbstractDocument $document
     * @return \FOS\RestBundle\View\View|AbstractDocument
     */
    protected function processForm(Request $request, AbstractDocument $document)
    {
        $type = $this->getDocumentFormType();
        $form = $this->createForm($type, $document);
        $form->submit($request);

        if ($form->isValid()) {
            $this->getDocumentRepository()->getDocumentManager()->persist($document);
            $this->getDocumentRepository()->getDocumentManager()->flush();
            return $document;
        } else {
            return $this->view($form, Codes::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param Request $request
     * @return \FOS\RestBundle\View\View|AbstractDocument
     */
    protected function processPost(Request $request)
    {
        $document = $this->getDocumentRepository()->createNew();
        return $this->processForm($request, $document);
    }

    /**
     * @param AbstractDocument $document
     * @return null
     */
    protected function processDelete(AbstractDocument $document)
    {
        $this->getDocumentRepository()->getDocumentManager()->remove($document);
        $this->getDocumentRepository()->getDocumentManager()->flush();
        return null;
    }
}
