<?php

namespace Lighthouse\JobBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use FOS\RestBundle\Controller\FOSRestController;
use Lighthouse\JobBundle\Document\Job\Job;
use Lighthouse\JobBundle\Document\Job\JobRepository;
use JMS\DiExtraBundle\Annotation as DI;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class JobController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.job.repository")
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
