<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Document\File\File;
use Lighthouse\CoreBundle\Document\File\FileRepository;
use Lighthouse\CoreBundle\Document\File\FileUploader;
use Lighthouse\CoreBundle\Exception\ValidationFailedException;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;

class FileController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.file")
     * @var FileRepository
     */
    protected $documentRepository;

    /**
     * @DI\Inject("lighthouse.core.document.repository.file.uploader")
     * @var FileUploader
     */
    protected $fileUploader;

    /**
     * @Rest\View(statusCode=201)
     *
     * @param Request $request
     * @return View|File
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function postFileAction(Request $request)
    {
        try {
            $file = $this->fileUploader->processRequest($request);
            $this->documentRepository->save($file);
            return $file;
        } catch (ValidationFailedException $e) {
            return $this->createFailedValidationView($e);
        }
    }

    /**
     * @param ValidationFailedException $e
     * @return View
     */
    protected function createFailedValidationView(ValidationFailedException $e)
    {
        $data = array(
            'code' => Response::HTTP_BAD_REQUEST,
            'message' => 'Validation failed',
            'errors' => array(),
        );
        foreach ($e->getConstraintViolationList() as $violationList) {
            $data['errors'][] = $violationList->getMessage();
        }
        return $this->view($data, Response::HTTP_BAD_REQUEST);
    }
}
