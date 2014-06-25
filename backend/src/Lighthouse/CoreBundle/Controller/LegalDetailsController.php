<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\LegalDetails\LegalDetails;
use Lighthouse\CoreBundle\Document\LegalDetails\LegalDetailsRepository;
use Lighthouse\CoreBundle\Form\LegalDetailsType;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class LegalDetailsController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.legal_details")
     * @var LegalDetailsRepository
     */
    protected $documentRepository;

    /**
     * @return LegalDetailsType
     */
    protected function getDocumentFormType()
    {
        return new LegalDetailsType();
    }

    /**
     * @param Request $request
     * @return FormInterface|LegalDetails
     *
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function postLegalDetailsAction(Request $request)
    {
        return $this->processForm($request);
    }
}
