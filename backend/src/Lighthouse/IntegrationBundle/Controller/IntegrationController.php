<?php

namespace Lighthouse\IntegrationBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;
use Lighthouse\CoreBundle\Document\Job\Integration\Set10\ExportProductsJob;
use Lighthouse\CoreBundle\Job\JobManager;
use Lighthouse\CoreBundle\Job\JobRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;

class IntegrationController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.job.repository")
     * @var JobRepository
     */
    protected $documentRepository;

    /**
     * @DI\Inject("lighthouse.core.job.manager")
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
