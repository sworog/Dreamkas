<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\StoreCollection;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Form\StoreType;
use Lighthouse\CoreBundle\Request\ParamConverter\Link;
use Lighthouse\CoreBundle\Request\ParamConverter\Links;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class StoreController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.store")
     * @var \Lighthouse\CoreBundle\Document\Store\StoreRepository
     */
    protected $documentRepository;

    /**
     * @return AbstractType
     */
    protected function getDocumentFormType()
    {
        return new StoreType();
    }

    /**
     * @Rest\View(statusCode=201)
     *
     * @param Request $request
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\Store\Store
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function postStoresAction(Request $request)
    {
        return $this->processPost($request);
    }

    /**
     * @Rest\View(statusCode=200)
     *
     * @param Request $request
     * @param \Lighthouse\CoreBundle\Document\Store\Store $store
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\Store\Store
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function putStoresAction(Request $request, Store $store)
    {
        return $this->processForm($request, $store);
    }

    /**
     * @return \Lighthouse\CoreBundle\Document\Store\StoreCollection
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(
     *      resource=true
     * )
     */
    public function getStoresAction()
    {
        $cursor = $this->documentRepository->findAll();
        $collection = new StoreCollection($cursor);
        return $collection;
    }

    /**
     * @param \Lighthouse\CoreBundle\Document\Store\Store $store
     * @return Store
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function getStoreAction(Store $store)
    {
        return $store;
    }

    /**
     * @param Store $store
     * @param Links|Link[] $links
     * @throws BadRequestHttpException
     * @throws ConflictHttpException
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function linkStoreAction(Store $store, Links $links)
    {
        foreach ($links as $link) {
            $user = $this->validateLink($link);
            if ($store->managers->contains($user)) {
                throw new ConflictHttpException(sprintf("User '%s' is already store manager", $user->username));
            }
            $store->managers->add($user);
        }
        $this->documentRepository->getDocumentManager()->persist($store);
        $this->documentRepository->getDocumentManager()->flush();
    }

    /**
     * @param Store $store
     * @param Links|Link[] $links
     * @throws BadRequestHttpException
     * @throws ConflictHttpException
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function unlinkStoreAction(Store $store, Links $links)
    {
        foreach ($links as $link) {
            $user = $this->validateLink($link);
            if (!$store->managers->contains($user)) {
                throw new ConflictHttpException(sprintf("User '%s' is not store manager", $user->username));
            }
            $store->managers->removeElement($user);
        }
        $this->documentRepository->getDocumentManager()->persist($store);
        $this->documentRepository->getDocumentManager()->flush();
    }

    /**
     * @param Link $link
     * @return User
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    protected function validateLink(Link $link)
    {
        if (User::ROLE_STORE_MANAGER != $link->getRel()) {
            throw new BadRequestHttpException(
                sprintf(
                    'Invalid rel given: %s, only %s is valid',
                    $link->getRel(),
                    User::ROLE_STORE_MANAGER
                )
            );
        }
        if (!$link->getResource() instanceof User) {
            throw new BadRequestHttpException("Invalid resource given, should be User");
        }
        /* @var User $user */
        $user = $link->getResource();
        if (!in_array(User::ROLE_STORE_MANAGER, $user->getRoles())) {
            throw new BadRequestHttpException(
                sprintf("User '%s' does not have store manager role", $link->getResource()->username)
            );
        }
        return $user;
    }
}
