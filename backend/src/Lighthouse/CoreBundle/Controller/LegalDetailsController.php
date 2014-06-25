<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\LegalDetails\LegalDetails;
use Lighthouse\CoreBundle\Document\LegalDetails\LegalDetailsRepository;
use Lighthouse\CoreBundle\Form\LegalDetailsType;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Lighthouse\CoreBundle\Document\Organization\Organization;
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
     * @param Organization $organization
     * @return FormInterface|LegalDetails
     *
     * @Rest\Route("organizations/{organization}/legalDetails")
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function postOrganizationLegalDetailsAction(Request $request, Organization $organization)
    {
        $form = $this->submitForm($request);

        if ($form->isValid()) {
            /* @var LegalDetails $legalDetails */
            $legalDetails = $form->getData();
            $legalDetails->organization = $organization;
            return $this->saveDocument($legalDetails, $form);
        } else {
            return $form;
        }
    }

    /**
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @Rest\Route("organizations/{organization}/legalDetails/{legalDetails}")
     * @ApiDoc
     *
     * @param Organization $organization
     * @param LegalDetails $legalDetails
     * @return LegalDetails
     */
    public function getOrganizationLegalDetailsAction(Organization $organization, LegalDetails $legalDetails)
    {
        return $legalDetails;
    }
}
