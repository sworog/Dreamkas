<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\FirstStart\FirstStart;
use Lighthouse\CoreBundle\Document\FirstStart\FirstStartRepository;
use Symfony\Component\Form\FormTypeInterface;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

class FirstStartController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.first_start")
     * @var FirstStartRepository
     */
    protected $documentRepository;

    /**
     * @return FormTypeInterface
     */
    protected function getDocumentFormType()
    {

    }

    /**
     * @return FirstStart
     * @Rest\Route("firstStart")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function getFirstStartAction()
    {
        return $this->documentRepository->findOrCreate();
    }
}
