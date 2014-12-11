<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Exception\FlushFailedException;
use Symfony\Component\Form\Extension\Validator\ViolationMapper\ViolationMapper;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Exception;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class AbstractRestController extends FOSRestController
{
    /**
     * @var DocumentRepository
     */
    protected $documentRepository;

    /**
     * @return FormTypeInterface
     */
    abstract protected function getDocumentFormType();

    /**
     * @param Request $request
     * @param AbstractDocument $document
     * @param FormTypeInterface $formType
     * @param bool $save
     * @param bool $clearMissing
     * @param ConstraintViolationListInterface $extraViolations
     * @return AbstractDocument|FormInterface
     */
    protected function processForm(
        Request $request,
        AbstractDocument $document = null,
        FormTypeInterface $formType = null,
        $save = true,
        $clearMissing = true,
        ConstraintViolationListInterface $extraViolations = null
    ) {
        return $this->processFormCallback(
            $request,
            array($this, 'saveDocument'),
            $document,
            $formType,
            $save,
            $clearMissing,
            $extraViolations
        );
    }

    /**
     * @param Request $request
     * @param callable $callback
     * @param AbstractDocument|mixed $document
     * @param FormTypeInterface $formType
     * @param bool $save
     * @param bool $clearMissing
     * @param ConstraintViolationListInterface $extraViolations
     * @return AbstractDocument|FormInterface
     */
    protected function processFormCallback(
        Request $request,
        $callback,
        $document = null,
        FormTypeInterface $formType = null,
        $save = true,
        $clearMissing = true,
        ConstraintViolationListInterface $extraViolations = null
    ) {
        $form = $this->submitForm($request, $document, $formType, $clearMissing);

        if ($extraViolations) {
            $this->addFormErrorByViolationList($form, $extraViolations);
        }

        if ($form->isValid()) {
            $document = ($document) ?: $form->getData();
            if ($save && !$this->isValidate($request)) {
                return call_user_func($callback, $document, $form);
            } else {
                return $document;
            }
        } else {
            return $form;
        }
    }

    /**
     * @param Request $request
     * @param FormTypeInterface $type
     * @param mixed $document
     * @param bool $clearMissing
     * @return FormInterface
     */
    protected function submitForm(
        Request $request,
        $document = null,
        FormTypeInterface $type = null,
        $clearMissing = true
    ) {
        $options = $this->getFormOptions($request);
        $type = ($type) ?: $this->getDocumentFormType();
        $form = $this->createForm($type, $document, $options);
        $form->submit($request, $clearMissing);

        return $form;
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getFormOptions(Request $request)
    {
        $validationGroups = $request->get('validationGroups', false);
        $options = array();
        if ($this->isValidate($request) && $validationGroups) {
            $options['validation_groups'] = $validationGroups;
        }
        return $options;
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function isValidate(Request $request)
    {
        return false != $request->get('validate', false);
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
     * @return FormInterface|AbstractDocument
     */
    protected function processPost(Request $request)
    {
        $document = $this->documentRepository->createNew();
        return $this->processForm($request, $document);
    }

    /**
     * @param AbstractDocument $document
     * @return void
     */
    protected function processDelete(AbstractDocument $document)
    {
        $this->documentRepository->getDocumentManager()->remove($document);
        $this->documentRepository->getDocumentManager()->flush();
    }

    /**
     * @param FormInterface $form
     * @param string $field
     * @param $messageTemplate
     * @param array $messageParameters
     * @return FormInterface
     */
    protected function addFormError(
        FormInterface $form,
        $field,
        $messageTemplate,
        array $messageParameters = array()
    ) {
        $propertyPath = ($field) ? 'data.' . $field : '';
        $root = ($field) ? $form->get($field) : $form;
        $violation = new ConstraintViolation(
            $messageTemplate,
            $messageTemplate,
            $messageParameters,
            $root,
            $propertyPath,
            null
        );
        $violationMapper = new ViolationMapper();
        $violationMapper->mapViolation($violation, $form);
        return $form;
    }

    /**
     * @param FormInterface $form
     * @param ConstraintViolationInterface $violation
     * @return FormInterface
     */
    protected function addFormErrorByViolation(FormInterface $form, ConstraintViolationInterface $violation)
    {
        return $this->addFormError(
            $form,
            $violation->getPropertyPath(),
            $violation->getMessageTemplate(),
            $violation->getMessageParameters()
        );
    }


    /**
     * @param FormInterface $form
     * @param ConstraintViolationListInterface|ConstraintViolationInterface[] $violationList
     * @return FormInterface
     */
    protected function addFormErrorByViolationList(FormInterface $form, ConstraintViolationListInterface $violationList)
    {
        foreach ($violationList as $violation) {
            $this->addFormErrorByViolation($form, $violation);
        }
        return $form;
    }
}
