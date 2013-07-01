<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\Klass\Klass;
use Lighthouse\CoreBundle\Document\Klass\KlassCollection;
use Lighthouse\CoreBundle\Document\Klass\KlassRepository;
use Lighthouse\CoreBundle\Form\KlassType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class KlassController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.klass")
     * @var KlassRepository
     */
    protected $documentRepository;

    /**
     * @return KlassType
     */
    protected function getDocumentFormType()
    {
        return new KlassType();
    }

    /**
     * @Rest\View(statusCode=201)
     *
     * @param Request $request
     * @return \FOS\RestBundle\View\View|Klass
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function postKlassesAction(Request $request)
    {
        return $this->processPost($request);
    }

    /**
     * @Rest\View(statusCode=200)
     *
     * @param Request $request
     * @param Klass $klass
     * @return \FOS\RestBundle\View\View|Klass
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function putKlassesAction(Request $request, Klass $klass)
    {
        return $this->processForm($request, $klass);
    }

    /**
     * @param Klass $klass
     * @return null
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function deleteKlassesAction(Klass $klass)
    {
        return $this->processDelete($klass);
    }

    /**
     * @param Klass $klass
     * @return Klass
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function getKlassAction(Klass $klass)
    {
        return $klass;
    }

    /**
     * @return KlassCollection
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(
     *      resource=true
     * )
     */
    public function getKlassesAction()
    {
        $cursor = $this->documentRepository->findAll();
        $collection = new KlassCollection($cursor);
        return $collection;
    }
}
