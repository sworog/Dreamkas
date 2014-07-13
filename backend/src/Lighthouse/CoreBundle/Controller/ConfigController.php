<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Config\Config;
use Lighthouse\CoreBundle\Document\Config\ConfigRepository;
use Lighthouse\CoreBundle\Form\ConfigType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
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
     * @param Request $request
     * @return FormInterface|Config
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
     * @param Request $request
     * @param Config $config
     * @return FormInterface|Config
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
     * @return Config
     * @ApiDoc
     * @Secure(roles="ROLE_ADMINISTRATOR")
     */
    public function getConfigAction(Config $config)
    {
        return $config;
    }

    /**
     * @return Cursor|Config[]
     * @ApiDoc(resource=true)
     * @Secure(roles="ROLE_ADMINISTRATOR")
     */
    public function getConfigsAction()
    {
        return $this->documentRepository->findAll();
    }

    /**
     * @param Request $request
     * @return Config
     * @ApiDoc
     * @Secure(roles="ROLE_ADMINISTRATOR")
     */
    public function getConfigsByNameAction(Request $request)
    {
        $query = $request->get('query');
        return $this->documentRepository->findOneBy(array('name' => $query));
    }
}
