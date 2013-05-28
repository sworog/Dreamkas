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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @Rest\View(statusCode=201)
     *
     * @param Request $request
     * @param string $klassId
     * @return \FOS\RestBundle\View\View|Group
     */
    public function postGroupsAction(Request $request, $klassId)
    {
        $klass = $this->findKlass($klassId);
        $group = new Group();
        $group->klass = $klass;

        return $this->processForm($request, $group);
    }

    /**
     * @param Request $request
     * @param string $klassId
     * @param string $groupId
     * @return \FOS\RestBundle\View\View|Group
     */
    public function putGroupsAction(Request $request, $klassId, $groupId)
    {
        $group = $this->findGroup($klassId, $groupId);
        return $this->processForm($request, $group);
    }

    /**
     * @param string $klassId
     * @param string $groupId
     * @return Group
     */
    public function getGroupAction($klassId, $groupId)
    {
        return $this->findGroup($klassId, $groupId);
    }

    /**
     * @param string $klassId
     * @return GroupCollection
     */
    public function getGroupsAction($klassId)
    {
        $klass = $this->findKlass($klassId);
        $cursor = $this->getDocumentRepository()->findByKlass($klass->id);
        $collection = new GroupCollection($cursor);
        return $collection;
    }

    /**
     * @param $klassId
     * @return Klass
     * @throws NotFoundHttpException
     */
    protected function findKlass($klassId)
    {
        $klass = $this->klassRepository->find($klassId);
        if (null === $klass) {
            throw new NotFoundHttpException("Klass not found");
        }
        return $klass;
    }

    /**
     * @param string $klassId
     * @param string $groupId
     * @return Group
     * @throws NotFoundHttpException
     */
    protected function findGroup($klassId, $groupId)
    {
        $klass = $this->findKlass($klassId);
        $group = $this->getDocumentRepository()->find($groupId);
        if (null === $group) {
            throw new NotFoundHttpException("Group not found");
        } elseif ($group->klass->id != $klass->id) {
            throw new NotFoundHttpException("Klass not found");
        }
        return $group;
    }
}
