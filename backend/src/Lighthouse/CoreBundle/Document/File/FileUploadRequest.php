<?php

namespace Lighthouse\CoreBundle\Document\File;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class FileUploadRequest
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @Assert\NotBlank(message="lighthouse.validation.errors.file_upload.name.not_blank")
     * @return string
     */
    public function getName()
    {
        return rawurldecode($this->request->headers->get('x-file-name'));
    }

    /**
     * @Assert\NotBlank(message="lighthouse.validation.errors.file_upload.length.not_blank")
     * @Assert\LessThanOrEqual(value=10485760, message="lighthouse.validation.errors.file_upload.length.length")
     * @return int
     */
    public function getLength()
    {
        return (int) $this->request->headers->get('content_length');
    }

    /**
     * @return resource|string
     */
    public function getFileResource()
    {
        try {
            return $this->request->getContent(true);
        } catch (\LogicException $e) {
            // FIXME Workaround when content was already fetched in \FOS\RestBundle\EventListener
            return $this->request->getContent(false);
        }
    }
}
