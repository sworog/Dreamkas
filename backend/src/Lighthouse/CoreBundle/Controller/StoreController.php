<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Document\Store\ManagerCollection;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\StoreCollection;
use Lighthouse\CoreBundle\Document\Store\StoreRepository;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Form\StoreType;
use Lighthouse\CoreBundle\Request\ParamConverter\Links\Link;
use Lighthouse\CoreBundle\Request\ParamConverter\Links\Links;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use InvalidArgumentException;

class StoreController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.store")
     * @var StoreRepository
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
     * @return View|Store
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
     * @param Store $store
     * @return View|Store
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function putStoresAction(Request $request, Store $store)
    {
        return $this->processForm($request, $store);
    }

    /**
     * @return StoreCollection
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
     * @param Store $store
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
            $managers = $this->getManagersByLink($link, $store);
            $manager = $link->getResource();
            if ($managers->contains($manager)) {
                throw new ConflictHttpException(sprintf("User '%s' is already store manager", $manager->username));
            }
            $this->checkManager($link);
            $managers->add($manager);
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
            $managers = $this->getManagersByLink($link, $store);
            $manager = $link->getResource();
            if (!$managers->contains($manager)) {
                throw new ConflictHttpException(sprintf("User '%s' is not store manager", $manager->username));
            }
            $managers->removeElement($manager);
        }
        $this->documentRepository->getDocumentManager()->persist($store);
        $this->documentRepository->getDocumentManager()->flush();
    }

    /**
     * @param User $user
     * @return StoreCollection|Store[]
     * @Secure(roles="ROLE_STORE_MANAGER,ROLE_DEPARTMENT_MANAGER")
     * @SecureParam(name="user", permissions="ACL_CURRENT_USER")
     * @ApiDoc
     */
    public function getUserStoresAction(User $user)
    {
        $cursor = $this->documentRepository->findByManagers($user->id);
        return new StoreCollection($cursor);
    }

    /**
     * @param Link $link
     * @param Store $store
     * @return ManagerCollection|User[]
     * @throws BadRequestHttpException
     */
    protected function getManagersByLink(Link $link, Store $store)
    {
        $rel = $link->getRel();
        try {
            return $store->getManagersByRel($rel);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException(
                sprintf(
                    'Invalid rel given: %s, should be %s or %s',
                    $rel,
                    Store::REL_STORE_MANAGERS,
                    Store::REL_DEPARTMENT_MANAGERS
                )
            );
        }
    }

    /**
     * @param Link $link
     * @throws BadRequestHttpException
     */
    protected function checkManager(Link $link)
    {
        $user = $link->getResource();
        if (!$user instanceof User) {
            throw new BadRequestHttpException('Invalid resource given, should be User');
        }
        $role = Store::getRoleByRel($link->getRel());
        if (!$user->hasUserRole($role)) {
            throw new BadRequestHttpException(
                sprintf("User '%s' does not have %s role", $user->username, $role)
            );
        }
    }
}
