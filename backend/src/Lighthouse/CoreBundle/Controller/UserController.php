<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\LoggableCursor;
use FOS\Rest\Util\Codes;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Document\User\UserCollection;
use Lighthouse\CoreBundle\Document\User\UserRepository;
use Lighthouse\CoreBundle\Form\UserType;
use Lighthouse\CoreBundle\Security\User\UserProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\SecurityContextInterface;

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
     * @return AbstractType
     */
    protected function getDocumentFormType()
    {
        return new UserType();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\User\User
     *
     * @Rest\View(statusCode=201)
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
     * @param string $id
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\User\User
     *
     * @Rest\View(statusCode=200)
     */
    public function putUsersAction(Request $request, $id)
    {
        /** @var User $document */
        $document = $this->findDocument($id);
        $originPassword = $document->password;

        $type = $this->getDocumentFormType();
        $form = $this->createForm($type, $document);
        $form->submit($request);

        if ($form->isValid()) {
            $requestPassword = $form->get('password')->getData();
            if (null === $requestPassword || '' == $requestPassword) {
                $document->password = $originPassword;
            } else {
                $this->userProvider->setPassword($document, $document->password);
            }
            $this->getDocumentRepository()->getDocumentManager()->persist($document);
            $this->getDocumentRepository()->getDocumentManager()->flush();
            return $document;
        } else {
            return $this->view($form, Codes::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @return User
     */
    public function getUsersCurrentAction()
    {
        return $this->securityContext->getToken()->getUser();
    }

    /**
     * @param int $id
     * @return User
     */
    public function getUserAction($id)
    {
        return $this->processGet($id);
    }

    /**
     * @return \Lighthouse\CoreBundle\Document\User\UserCollection
     */
    public function getUsersAction()
    {
        /* @var LoggableCursor $cursor */
        $cursor = $this->getDocumentRepository()->findAll();
        $collection = new UserCollection($cursor);
        return $collection;
    }
}
