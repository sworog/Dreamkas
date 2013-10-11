<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Lighthouse\CoreBundle\Document\Log\LogCollection;
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
     * @return LogCollection
     * @ApiDoc(
     *      resource=true
     * )
     */
    public function getLogsAction()
    {
        $cursor = $this->documentRepository->findAll();
        return new LogCollection($cursor);
    }
}
