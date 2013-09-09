<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\MongoDB\LoggableCursor;
use Lighthouse\CoreBundle\Document\Config\Config;
use Lighthouse\CoreBundle\Document\Config\ConfigCollection;
use Lighthouse\CoreBundle\Document\Config\ConfigRepository;
use Lighthouse\CoreBundle\Form\ConfigType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;

class ConfigController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.config")
     * @var ConfigRepository
     */
    protected $documentRepository;

    /**
     * @return AbstractType
     */
    protected function getDocumentFormType()
    {
        return new ConfigType();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\Config\Config
     *
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_ADMINISTRATOR")
     * @ApiDoc
     */
    public function postConfigsAction(Request $request)
    {
        return $this->processPost($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param Config $config
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\Config\Config
     *
     * @Rest\View(statusCode=200)
     * @Secure(roles="ROLE_ADMINISTRATOR")
     * @ApiDoc
     */
    public function putConfigsAction(Request $request, Config $config)
    {
        return $this->processForm($request, $config);
    }

    /**
     * @param Config $config
     * @return \Lighthouse\CoreBundle\Document\Config\Config
     * @ApiDoc
     * @Secure(roles="ROLE_ADMINISTRATOR")
     */
    public function getConfigAction(Config $config)
    {
        return $config;
    }

    /**
     * @return \Lighthouse\CoreBundle\Document\Config\ConfigCollection
     * @ApiDoc(
     *      resource=true
     * )
     * @Secure(roles="ROLE_ADMINISTRATOR")
     */
    public function getConfigsAction()
    {
        /* @var LoggableCursor $cursor */
        $cursor = $this->getDocumentRepository()->findAll();
        $collection = new ConfigCollection($cursor);
        return $collection;
    }
}
