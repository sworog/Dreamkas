<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\LoggableCursor;
use FOS\Rest\Util\Codes;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Document\User\UserCollection;
use Lighthouse\CoreBundle\Document\User\UserRepository;
use Lighthouse\CoreBundle\Form\UserType;
use Lighthouse\CoreBundle\Security\PermissionExtractor;
use Lighthouse\CoreBundle\Security\User\UserProvider;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;

class UserController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.user")
     * @var UserRepository
     */
    protected $documentRepository;

    /**
     * @DI\Inject("lighthouse.core.user.provider")
     * @var \Lighthouse\CoreBundle\Security\User\UserProvider
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
     * @return UserType
     */
    protected function getDocumentFormType()
    {
        return new UserType();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Lighthouse\CoreBundle\Document\User\User|\FOS\RestBundle\View\View
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
        $document = $this->getDocumentRepository()->createNew();

        $type = $this->getDocumentFormType();
        $form = $this->createForm($type, $document, array('validation_groups' => array('Default', 'registration')));
        $form->submit($request);

        if ($form->isValid()) {
            $this->userProvider->setPassword($document, $document->password);

            $this->getDocumentRepository()->getDocumentManager()->persist($document);
            $this->getDocumentRepository()->getDocumentManager()->flush();
            return $document;
        } else {
            return $this->view($form, Codes::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param User $user User ID
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\User\User
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
            $this->getDocumentRepository()->getDocumentManager()->persist($user);
            $this->getDocumentRepository()->getDocumentManager()->flush();
            return $user;
        } else {
            return $this->view($form, Codes::HTTP_BAD_REQUEST);
        }
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
     * @return \Lighthouse\CoreBundle\Document\User\UserCollection
     * @Secure(roles="ROLE_ADMINISTRATOR,ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(
     *      description="Create users"
     * )
     */
    public function getUsersAction()
    {
        /* @var LoggableCursor $cursor */
        $cursor = $this->getDocumentRepository()->findAll();
        $collection = new UserCollection($cursor);
        return $collection;
    }
}
