<?php

namespace Lighthouse\IntegrationBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Lighthouse\IntegrationBundle\Document\Integration\Set10\ExportProductsJob;
use Lighthouse\JobBundle\Job\JobManager;
use Lighthouse\JobBundle\Document\Job\JobRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class IntegrationController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.job.repository")
     * @var JobRepository
     */
    protected $documentRepository;

    /**
     * @DI\Inject("lighthouse.job.manager")
     * @var JobManager
     */
    protected $jobManager;

    /**
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function getIntegrationExportProductsAction()
    {
        $job = new ExportProductsJob();
        $this->jobManager->addJob($job);

        return array('status' => 'success');
    }
}
