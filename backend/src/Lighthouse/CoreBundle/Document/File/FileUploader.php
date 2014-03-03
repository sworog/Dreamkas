<?php

namespace Lighthouse\CoreBundle\Document\File;

use Lighthouse\CoreBundle\OpenStack\ObjectStore\Resource\Container;
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
     * @var string
     */
    protected $fileNameHeaderName = 'x-file-name';

    /**
     * @var resource
     */
    protected $fileResource;

    /**
     * @DI\InjectParams({
     *      "storageContainer" = @DI\Inject("openstack.selectel.storage.container")
     * })
     * @param Container $storageContainer
     */
    public function __construct(Container $storageContainer)
    {
        $this->storageContainer = $storageContainer;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getFileResource(Request $request)
    {
        if (!$this->fileResource) {
            $this->setFileResource($request->getContent(true));
        }
        return $this->fileResource;
    }

    /**
     * @param $fileResource
     */
    public function setFileResource($fileResource)
    {
        $this->fileResource = $fileResource;
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function getFileName(Request $request)
    {
        return $request->headers->get($this->fileNameHeaderName);
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
     * @param Request $request
     * @return File
     */
    public function processRequest(Request $request)
    {
        $fileResource = $this->getFileResource($request);

        $fileName = $this->getFileName($request);

        $file = new File();
        $file->name = $fileName;

        $headers = new ResponseHeaderBag();
        $headers->set(
            'Content-Disposition',
            $headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $file->name)
        );

        $dataObject = $this->storageContainer->uploadObject($file->id, $fileResource, $headers->all());
        $dataObject->retrieveMetadata();

        $this->populateFileByDataObject($file, $dataObject);

        return $file;
    }
}
