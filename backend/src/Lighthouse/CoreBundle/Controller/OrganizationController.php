<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Document\Organization\Organization;
use Lighthouse\CoreBundle\Document\Organization\OrganizationRepository;
use Lighthouse\CoreBundle\Form\OrganizationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;

class OrganizationController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.organization")
     * @var OrganizationRepository
     */
    protected $documentRepository;

    /**
     * @return AbstractType
     */
    protected function getDocumentFormType()
    {
        return new OrganizationType();
    }

    /**
     * @param Request $request
     * @return View|Organization
     *
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function postOrganizationAction(Request $request)
    {
        return $this->processPost($request);
    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @return View|Organization
     *
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     */
    public function putOrganizationAction(Request $request, Organization $organization)
    {
        return $this->processForm($request, $organization);
    }

    /**
     * @param Organization $organization
     * @return Organization
     *
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     */
    public function getOrganizationAction(Organization $organization)
    {
        return $organization;
    }
}
