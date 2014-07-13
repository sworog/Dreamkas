<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use FOS\RestBundle\Controller\FOSRestController;
use Lighthouse\CoreBundle\Document\Job\Job;
use Lighthouse\CoreBundle\Job\JobRepository;
use JMS\DiExtraBundle\Annotation as DI;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class JobController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.job.repository")
     * @var JobRepository
     */
    protected $documentRepository;

    /**
     * @return Job[]|Cursor
     * @ApiDoc(resource=true)
     */
    public function getJobsAction()
    {
        return $this->documentRepository->findAll();
    }
}
