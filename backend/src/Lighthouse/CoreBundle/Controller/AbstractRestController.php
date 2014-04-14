<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Exception\FlushFailedException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Validator\ViolationMapper\ViolationMapper;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Exception;

abstract class AbstractRestController extends FOSRestController
{
    /**
     * @var DocumentRepository
     */
    protected $documentRepository;

    /**
     * @return AbstractType
     */
    abstract protected function getDocumentFormType();

    /**
     * @param Request $request
     * @param AbstractDocument $document
     * @throws FlushFailedException
     * @param bool $save
     * @return View|AbstractDocument
     */
    protected function processForm(Request $request, AbstractDocument $document, $save = true)
    {
        $validation = $request->get('validation', false);
        $options = array();
        if ($validation && '1' !== $validation) {
            $options['validation_groups'] = $validation;
        }
        $type = $this->getDocumentFormType();
        $form = $this->createForm($type, $document, $options);
        $form->submit($request);

        if ($form->isValid()) {
            if (true == $save && false == $validation) {
                return $this->saveDocument($document, $form);
            } else {
                return $document;
            }
        } else {
            return $form;
        }
    }

    /**
     * @param AbstractDocument $document
     * @param FormInterface $form
     * @return AbstractDocument|FormInterface
     */
    protected function saveDocument(AbstractDocument $document, FormInterface $form)
    {
        try {
            $this->documentRepository->getDocumentManager()->persist($document);
            $this->documentRepository->getDocumentManager()->flush();
            return $document;
        } catch (Exception $e) {
            return $this->handleFlushFailedException(new FlushFailedException($e, $form));
        }
    }

    /**
     * @param FlushFailedException $e
     * @throws Exception
     * @return FormInterface|AbstractDocument
     */
    protected function handleFlushFailedException(FlushFailedException $e)
    {
        throw $e->getCause();
    }

    /**
     * @param Request $request
     * @return View|AbstractDocument
     */
    protected function processPost(Request $request)
    {
        $document = $this->documentRepository->createNew();
        return $this->processForm($request, $document);
    }

    /**
     * @param AbstractDocument $document
     * @return null
     */
    protected function processDelete(AbstractDocument $document)
    {
        $this->documentRepository->getDocumentManager()->remove($document);
        $this->documentRepository->getDocumentManager()->flush();
        return null;
    }

    /**
     * @param FormInterface $form
     * @param string $field
     * @param $messageTemplate
     * @param array $messageParameters
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function addFormError(
        FormInterface $form,
        $field,
        $messageTemplate,
        array $messageParameters = array()
    ) {
        $violation = new ConstraintViolation(
            $messageTemplate,
            $messageTemplate,
            $messageParameters,
            $form->get($field),
            'data.' . $field,
            null
        );
        $violationMapper = new ViolationMapper();
        $violationMapper->mapViolation($violation, $form);
        return $form;
    }
}
