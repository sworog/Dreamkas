<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\Rest\Util\Codes;
use FOS\RestBundle\Controller\FOSRestController;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @param string $id
     * @return AbstractDocument
     * @throws NotFoundHttpException
     */
    protected function findDocument($id)
    {
        $document = $this->getDocumentRepository()->find($id);
        if (null === $document) {
            throw new NotFoundHttpException(
                sprintf(
                    '%s not found',
                    $this->getDocumentRepository()->getClassMetadata()->getReflectionClass()->getShortName()
                )
            );
        }
        return $document;
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
     * @param Request $request
     * @param string $id
     * @return \FOS\RestBundle\View\View|AbstractDocument
     */
    protected function processPut(Request $request, $id)
    {
        $document = $this->findDocument($id);
        return $this->processForm($request, $document);
    }

    /**
     * @param string $id
     * @return null
     */
    protected function processDelete($id)
    {
        $document = $this->findDocument($id);
        $this->getDocumentRepository()->getDocumentManager()->remove($document);
        $this->getDocumentRepository()->getDocumentManager()->flush();
        return null;
    }

    /**
     * @param int $id
     * @return \Lighthouse\CoreBundle\Document\AbstractDocument
     */
    protected function processGet($id)
    {
        return $this->findDocument($id);
    }
}
