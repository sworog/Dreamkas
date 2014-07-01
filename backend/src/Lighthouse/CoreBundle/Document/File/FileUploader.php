<?php

namespace Lighthouse\CoreBundle\Document\File;

use Lighthouse\CoreBundle\Exception\ValidationFailedException;
use Lighthouse\CoreBundle\OpenStack\ObjectStore\Resource\Container;
use Lighthouse\CoreBundle\Validator\ExceptionalValidator;
use OpenCloud\ObjectStore\Resource\DataObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.document.repository.file.uploader")
 */
class FileUploader
{
    /**
     * @var Container
     */
    protected $storageContainer;

    /**
     * @var ExceptionalValidator
     */
    protected $validator;

    /**
     * @var resource|string
     */
    protected $fileResource;

    /**
     * @DI\InjectParams({
     *      "storageContainer" = @DI\Inject("openstack.selectel.storage.container"),
     *      "validator" = @DI\Inject("lighthouse.core.validator")
     * })
     * @param Container $storageContainer
     * @param ExceptionalValidator $validator
     */
    public function __construct(Container $storageContainer, ExceptionalValidator $validator)
    {
        $this->storageContainer = $storageContainer;
        $this->validator = $validator;
    }

    /**
     * @param FileUploadRequest $fileUploadRequest
     * @return resource|string
     */
    public function getFileResource(FileUploadRequest $fileUploadRequest)
    {
        if (null === $this->fileResource) {
            $this->setFileResource($fileUploadRequest->getFileResource());
        }
        return $this->fileResource;
    }

    /**
     * @param resource|string $fileResource
     */
    public function setFileResource($fileResource)
    {
        $this->fileResource = $fileResource;
    }

    /**
     * @param File $file
     * @param DataObject $dataObject
     */
    protected function populateFileByDataObject(File $file, DataObject $dataObject)
    {
        $file->url = (string) $dataObject->getUrl();
        $file->size = (int) $dataObject->getContentLength();
    }

    /**
     * @param FileUploadRequest $fileUploadRequest
     * @throws ValidationFailedException
     */
    public function validateRequest(FileUploadRequest $fileUploadRequest)
    {
        $this->validator->validate($fileUploadRequest);
    }

    /**
     * @param Request $request
     * @return File
     * @throws ValidationFailedException
     */
    public function processRequest(Request $request)
    {
        $fileUploadRequest = new FileUploadRequest($request);

        $this->validateRequest($fileUploadRequest);

        $fileResource = $this->getFileResource($fileUploadRequest);

        $file = new File();
        $file->name = $fileUploadRequest->getName();

        $headers = new ResponseHeaderBag();
        $headers->set(
            'Content-Disposition',
            $headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $file->name, $file->id)
        );

        $dataObject = $this->storageContainer->uploadObject($file->id, $fileResource, $headers->all());
        $dataObject->retrieveMetadata();

        $this->populateFileByDataObject($file, $dataObject);

        return $file;
    }

    /**
     * @param \PHPExcel_Writer_Abstract $writer
     * @param string $filename
     * @param string|null $directory
     * @return File
     */
    public function processWriter(\PHPExcel_Writer_Abstract $writer, $filename, $directory = null)
    {
        $tmpFilename = tempnam('/tmp', 'order_generator');
        $writer->save($tmpFilename);

        $file = new File();
        $file->name = $filename;

        if (null == $directory) {
            $filePath = $filename;
        } else {
            $filePath = $directory . '/' . $filename;
        }

        $headers = new ResponseHeaderBag();
        $headers->set(
            'X-Delete-After',
            60 * 60
        );

        $fileResource = fopen($tmpFilename, 'rb');
        $dataObject = $this->storageContainer->uploadObject($filePath, $fileResource, $headers->all());
        $dataObject->retrieveMetadata();

        $this->populateFileByDataObject($file, $dataObject);

        return $file;
    }
}
