<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Document\File\File;
use Lighthouse\CoreBundle\Document\File\FileRepository;
use Lighthouse\CoreBundle\OpenStack\ObjectStore\Resource\Container;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class FileController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.file")
     * @var FileRepository
     */
    protected $documentRepository;

    /**
     * @DI\Inject("openstack.selectel.storage.container")
     * @var Container
     */
    protected $storageContainer;

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
        $fileResource = $request->getContent(true);
        //$fileResource = fopen(__FILE__, 'rb');
        $fileName = $request->headers->get('x-file-name');
        /* @var File $file */
        $file = $this->documentRepository->createNew();

        $headers = array(
            'Content-Disposition' => 'attachment; filename="'. $fileName . '"'
        );
        //$headers = Container::stockHeaders($meta);
        $dataObject = $this->storageContainer->uploadObject($file->id, $fileResource, $headers);
        $dataObject->retrieveMetadata();

        $file->name = $fileName;
        $file->url = (string) $dataObject->getUrl();
        $file->size = $dataObject->getContentLength();

        $this->documentRepository->save($file);

        return $file;
    }
}
