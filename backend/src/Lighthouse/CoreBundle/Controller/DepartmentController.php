<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\Department\Department;
use Lighthouse\CoreBundle\Document\Department\DepartmentCollection;
use Lighthouse\CoreBundle\Document\Department\DepartmentRepository;
use Lighthouse\CoreBundle\Form\DepartmentType;
use Lighthouse\CoreBundle\Document\Store\Store;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;

class DepartmentController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.department")
     * @var DepartmentRepository
     */
    protected $documentRepository;

    /**
     * @return DepartmentType
     */
    protected function getDocumentFormType()
    {
        return new DepartmentType();
    }

    /**
     * @param Request $request
     * @return \FOS\RestBundle\View\View|Department
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(
     *      resource=true
     * )
     */
    public function postDepartmentsAction(Request $request)
    {
        return $this->processPost($request);
    }

    /**
     * @param Request $request
     * @param \Lighthouse\CoreBundle\Document\Department\Department $department
     * @return \FOS\RestBundle\View\View|Department
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function putDepartmentsAction(Request $request, Department $department)
    {
        return $this->processForm($request, $department);
    }

    /**
     * @param Department $department
     * @return Department
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function getDepartmentAction(Department $department)
    {
        return $department;
    }

    /**
     * @param \Lighthouse\CoreBundle\Document\Store\Store $store
     * @return DepartmentCollection
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function getStoreDepartmentsAction(Store $store)
    {
        $cursor = $this->documentRepository->findByStore($store->id);
        $collection = new DepartmentCollection($cursor);
        return $collection;
    }
}
