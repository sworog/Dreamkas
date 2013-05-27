<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\Rest\Util\Codes;
use FOS\RestBundle\Controller\FOSRestController;
use Lighthouse\CoreBundle\Document\Klass\KlassRepository;
use Lighthouse\CoreBundle\Form\KlassType;
use Symfony\Component\Form\AbstractType;
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
     * @return AbstractType
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
}
