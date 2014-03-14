<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
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
     * @param Request $request
     * @param AbstractDocument $document
     * @param bool $save
     * @return View|AbstractDocument
     */
    protected function processForm(Request $request, AbstractDocument $document, $save = true)
    {
        $validation = $request->get('validation', false);
        $type = $this->getDocumentFormType();
        $form = $this->createForm($type, $document);
        $form->submit($request);

        if ($form->isValid()) {
            if (true == $save && false == $validation) {
                $this->getDocumentRepository()->getDocumentManager()->persist($document);
                $this->getDocumentRepository()->getDocumentManager()->flush();
            }
            return $document;
        } else {
            return $form;
        }
    }

    /**
     * @param Request $request
     * @return View|AbstractDocument
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
