<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\Job\JobCollection;
use Lighthouse\CoreBundle\Document\Job\JobRepository;
use Symfony\Component\Form\AbstractType;
use JMS\DiExtraBundle\Annotation as DI;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class JobController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.job.repository")
     * @var JobRepository
     */
    protected $documentRepository;

    /**
     * @return AbstractType
     */
    protected function getDocumentFormType()
    {
    }

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
