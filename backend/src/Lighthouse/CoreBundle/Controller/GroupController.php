<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\Classifier\Group\GroupCollection;
use Lighthouse\CoreBundle\Document\Classifier\Group\GroupRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Exception\FlushFailedException;
use Lighthouse\CoreBundle\Form\GroupType;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use MongoDuplicateKeyException;

class GroupController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.classifier.group")
     * @var GroupRepository
     */
    protected $documentRepository;

    /**
     * @DI\Inject("translator")
     * @var Translator
     */
    protected $translator;

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
     * @throws \Exception
     * @throws \Lighthouse\CoreBundle\Exception\FlushFailedException
     * @return View|Group
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function postGroupsAction(Request $request)
    {
        try {
            return $this->processPost($request);
        } catch (FlushFailedException $e) {
            if ($e->getCause() instanceof MongoDuplicateKeyException) {
                return $this->addFormError($e->getForm(), 'name', 'lighthouse.validation.errors.group.name.unique');
            } else {
                throw $e;
            }
        }
    }

    /**
     * @Rest\View(statusCode=200)
     *
     * @param Request $request
     * @param Group $group
     * @return View|Group
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function putGroupsAction(Request $request, Group $group)
    {
        return $this->processForm($request, $group);
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
     * @param Store $store
     * @param Group $group
     * @return Group
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER,ACL_DEPARTMENT_MANAGER")
     */
    public function getStoreGroupAction(Store $store, Group $group)
    {
        return $group;
    }

    /**
     * @return GroupCollection
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(
     *      resource=true
     * )
     */
    public function getGroupsAction()
    {
        $cursor = $this->documentRepository->findAll();
        return new GroupCollection($cursor);
    }

    /**
     * @param Store $store
     * @return GroupCollection
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER,ACL_DEPARTMENT_MANAGER")
     * @ApiDoc(
     *      resource=true
     * )
     */
    public function getStoreGroupsAction(Store $store)
    {
        $cursor = $this->documentRepository->findAll();
        return new GroupCollection($cursor);
    }
}
