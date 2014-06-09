<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\StoreRepository;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Document\User\UserCollection;
use Lighthouse\CoreBundle\Document\User\UserRepository;
use Lighthouse\CoreBundle\Exception\FlushFailedException;
use Lighthouse\CoreBundle\Form\User\CurrentUserType;
use Lighthouse\CoreBundle\Form\User\UserRestorePasswordType;
use Lighthouse\CoreBundle\Form\User\UserType;
use Lighthouse\CoreBundle\Security\PermissionExtractor;
use Lighthouse\CoreBundle\Security\User\UserProvider;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use MongoDuplicateKeyException;

class UserController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.user")
     * @var UserRepository
     */
    protected $documentRepository;

    /**
     * @DI\Inject("lighthouse.core.document.repository.store")
     * @var StoreRepository
     */
    protected $storeRepository;

    /**
     * @DI\Inject("lighthouse.core.user.provider")
     * @var UserProvider
     */
    public $userProvider;

    /**
     * @DI\Inject("security.context")
     * @var SecurityContextInterface
     */
    public $securityContext;

    /**
     * @DI\Inject("lighthouse.core.security.permissions_extractor")
     * @var PermissionExtractor
     */
    protected $permissionExtractor;

    /**
     * @return \Lighthouse\CoreBundle\Form\User\UserType
     */
    protected function getDocumentFormType()
    {
        return new UserType();
    }

    /**
     * @param FlushFailedException $e
     * @return FormInterface
     */
    protected function handleFlushFailedException(FlushFailedException $e)
    {
        if ($e->getCause() instanceof MongoDuplicateKeyException) {
            return $this->addFormError($e->getForm(), 'email', 'lighthouse.validation.errors.user.email.unique');
        } else {
            return parent::handleFlushFailedException($e);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return User|View
     *
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_ADMINISTRATOR")
     * @ApiDoc(
     *      resource=true,
     *      description="Create user",
     *      input="Lighthouse\CoreBundle\Form\UserType"
     * )
     *
     */
    public function postUsersAction(Request $request)
    {
        /** @var User $document */
        $document = $this->documentRepository->createNew();

        $type = $this->getDocumentFormType();
        $form = $this->createForm($type, $document, array('validation_groups' => array('Default', 'creation')));
        $form->submit($request);

        if ($form->isValid()) {
            $this->userProvider->setPassword($document, $document->password);
            return $this->saveDocument($document, $form);
        } else {
            return $form;
        }
    }

    /**
     * @param Request $request
     * @return User|Form
     * @ApiDoc
     */
    public function putUsersCurrentAction(Request $request)
    {
        $user = $this->securityContext->getToken()->getUser();

        $form = $this->createForm(new CurrentUserType(), $user);
        $form->submit($request);
        if ($form->isValid()) {
            $this->userProvider->setPassword($user, $user->password);
            $this->saveDocument($user, $form);
            return $user;
        }
        return $form;
    }

    /**
     * @param Request $request
     * @param User $user User ID
     * @return View|User
     * @Secure(roles="ROLE_ADMINISTRATOR")
     * @ApiDoc(
     *      description="Update user",
     *      input="Lighthouse\CoreBundle\Form\UserType"
     * )
     */
    public function putUsersAction(Request $request, User $user)
    {
        $originPassword = $user->password;

        $type = $this->getDocumentFormType();
        $form = $this->createForm($type, $user);
        $form->submit($request);

        if ($form->isValid()) {
            $requestPassword = $form->get('password')->getData();
            if (null === $requestPassword || '' == $requestPassword) {
                $user->password = $originPassword;
            } else {
                $this->userProvider->setPassword($user, $user->password);
            }
            return $this->saveDocument($user, $form);
        } else {
            return $form;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return User|View
     *
     * @Rest\View(statusCode=201)
     * @Rest\Route("users/signup")
     * @ApiDoc(
     *      resource=true,
     *      description="Create user",
     *      input="Lighthouse\CoreBundle\Form\UserType"
     * )
     *
     */
    public function postUsersSignupAction(Request $request)
    {
        /** @var User $user */
        $user = $this->documentRepository->createNew();

        $type = $this->getDocumentFormType();
        $form = $this->createForm($type, $user, array('validation_groups' => array('registration')));
        $form->submit($request);

        if ($form->isValid()) {
            $user->roles = User::getDefaultRoles();
            $user->project = new Project();
            $password = $this->userProvider->generateUserPassword();
            $this->userProvider->setPassword($user, $password);
            $user = $this->saveDocument($user, $form);
            if ($user instanceof User) {
                return $this->userProvider->sendRegisteredMessage($user, $password);
            }
            return $user;
        } else {
            return $form;
        }
    }

    /**
     * @param Request $request
     *
     * @Rest\Route("users/restorePassword")
     * @Rest\View
     * @return View
     */
    public function postUsersRestorePasswordAction(Request $request)
    {
        $form = $this->createForm(new UserRestorePasswordType());
        $form->submit($request);
        if ($form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $this->documentRepository->findOneByEmail($email);
            $this->userProvider->restoreUserPassword($user);
            return array('email' => $email);
        }
        return $form;
    }

    /**
     * @return User
     * @ApiDoc(
     *      description="Get current logged user"
     * )
     */
    public function getUsersCurrentAction()
    {
        return $this->securityContext->getToken()->getUser();
    }

    /**
     * @return array
     * @ApiDoc(
     *      description="Get current logged user permissions"
     * )
     */
    public function getUsersPermissionsAction()
    {
        return $this->permissionExtractor->extractForCurrentUser($this->securityContext);
    }

    /**
     * @param User $user
     * @return User
     * @Secure(roles="ROLE_ADMINISTRATOR,ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(
     *      description="Get user"
     * )
     */
    public function getUserAction(User $user)
    {
        return $user;
    }

    /**
     * @return UserCollection
     * @Secure(roles="ROLE_ADMINISTRATOR")
     * @ApiDoc(
     *      description="Create users"
     * )
     */
    public function getUsersAction()
    {
        $users = $this->documentRepository->findAll();
        return new UserCollection($users);
    }

    /**
     * @param Store $store
     * @param Request $request
     * @return UserCollection
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @Rest\Route("stores/{store}/storeManagers")
     * @ApiDoc
     */
    public function getStoreStoreManagersAction(Store $store, Request $request)
    {
        $candidates = (bool) $request->query->get('candidates', false);
        if ($candidates) {
            $excludeIds = $this->storeRepository->findAllStoresManagerIds();
            $users = $this->documentRepository->findAllByRoles(User::ROLE_STORE_MANAGER, $excludeIds);
            return new UserCollection($users);
        } else {
            return new UserCollection($store->storeManagers);
        }
    }

    /**
     * @param Store $store
     * @param Request $request
     * @return UserCollection
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @Rest\Route("stores/{store}/departmentManagers")
     * @ApiDoc
     */
    public function getStoreDepartmentManagersAction(Store $store, Request $request)
    {
        $candidates = (bool) $request->query->get('candidates', false);
        if ($candidates) {
            $excludeIds = $this->storeRepository->findAllDepartmentManagerIds();
            $users = $this->documentRepository->findAllByRoles(User::ROLE_DEPARTMENT_MANAGER, $excludeIds);
            return new UserCollection($users);
        } else {
            return new UserCollection($store->departmentManagers);
        }
    }
}
