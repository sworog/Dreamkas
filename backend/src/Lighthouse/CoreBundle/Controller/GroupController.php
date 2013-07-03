<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\Group\Group;
use Lighthouse\CoreBundle\Document\Group\GroupCollection;
use Lighthouse\CoreBundle\Document\Group\GroupRepository;
use Lighthouse\CoreBundle\Document\Klass\KlassRepository;
use Lighthouse\CoreBundle\Form\GroupType;
use Lighthouse\CoreBundle\Document\Klass\Klass;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;

class GroupController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.group")
     * @var GroupRepository
     */
    protected $documentRepository;

    /**
     * @DI\Inject("lighthouse.core.document.repository.klass")
     * @var KlassRepository
     */
    protected $klassRepository;

    /**
     * @return GroupType
     */
    protected function getDocumentFormType()
    {
        return new GroupType();
    }

    /**
     * @param Request $request
     * @return \FOS\RestBundle\View\View|Group
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(
     *      resource=true
     * )
     */
    public function postGroupsAction(Request $request)
    {
        return $this->processPost($request);
    }

    /**
     * @param Request $request
     * @param Group $group
     * @return \FOS\RestBundle\View\View|Group
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function putGroupsAction(Request $request, Group $group)
    {
        return $this->processForm($request, $group);
    }

    /**
     * @param Group $group
     * @return Group
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function getGroupAction(Group $group)
    {
        return $group;
    }

    /**
     * @param Klass $klass
     * @return GroupCollection
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function getKlassGroupsAction(Klass $klass)
    {
        $cursor = $this->getDocumentRepository()->findByKlass($klass->id);
        $collection = new GroupCollection($cursor);
        return $collection;
    }

    /**
     * @param Group $group
     * @return null
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function deleteGroupsAction(Group $group)
    {
        return $this->processDelete($group);
    }
}
