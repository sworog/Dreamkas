<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use FOS\RestBundle\Controller\FOSRestController;
use Lighthouse\CoreBundle\Document\Log\Log;
use Lighthouse\CoreBundle\Document\Log\LogRepository;
use JMS\DiExtraBundle\Annotation as DI;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class LogController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.log")
     * @var LogRepository
     */
    protected $documentRepository;

    /**
     * @return Cursor|Log[]
     * @ApiDoc(resource=true)
     */
    public function getLogsAction()
    {
        return $this->documentRepository->findAll();
    }
}
