<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\StoreCollection;
use Lighthouse\CoreBundle\Form\StoreType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

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
}
