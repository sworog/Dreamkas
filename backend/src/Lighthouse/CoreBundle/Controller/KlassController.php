<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\Klass\Klass;
use Lighthouse\CoreBundle\Document\Klass\KlassCollection;
use Lighthouse\CoreBundle\Document\Klass\KlassRepository;
use Lighthouse\CoreBundle\Form\KlassType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;

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
     */
    public function postKlassesAction(Request $request)
    {
        return $this->processPost($request);
    }

    /**
     * @Rest\View(statusCode=200)
     *
     * @param Request $request
     * @param $id
     * @return \FOS\RestBundle\View\View|Klass
     */
    public function putKlassesAction(Request $request, $id)
    {
        return $this->processPut($request, $id);
    }

    /**
     * @param string $id
     * @return null
     */
    public function deleteKlassesAction($id)
    {
        return $this->processDelete($id);
    }

    /**
     * @param $id
     * @return Klass
     */
    public function getKlassAction($id)
    {
        return $this->processGet($id);
    }

    /**
     * @return KlassCollection
     */
    public function getKlassesAction()
    {
        $cursor = $this->documentRepository->findAll();
        $collection = new KlassCollection($cursor);
        return $collection;
    }
}
