<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Lighthouse\CoreBundle\Job\JobCollection;
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
     * @return JobCollection
     * @ApiDoc(
     *      resource=true
     * )
     */
    public function getJobsAction()
    {
        $cursor = $this->documentRepository->findAll();
        return new JobCollection($cursor);
    }
}
