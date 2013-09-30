<?php

namespace Lighthouse\CoreBundle\Security;

use Doctrine\Common\Annotations\Reader;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Security\Voter\StoreManagerVoter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Nelmio\ApiDocBundle\Extractor\ApiDocExtractor;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @DI\Service("lighthouse.core.security.permissions_extractor")
 */
class PermissionExtractor
{
    /**
     * @var string
     */
    protected $restPrefix = '/api/1/';

    /**
     * @var string
     */
    protected $restSuffix = '.{_format}';

    /**
     * @var ApiDocExtractor
     */
    protected $apiDocExtractor;

    /**
     *
     * @var Reader
     */
    protected $reader;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var StoreManagerVoter
     */
    protected $storeManagerVoter;

    /**
     * @param ApiDocExtractor $apiDocExtractor
     * @param Reader $reader
     * @param RouterInterface $router
     * @param StoreManagerVoter $storeManagerVoter
     *
     * @DI\InjectParams({
     *      "apiDocExtractor" = @DI\Inject("nelmio_api_doc.extractor.api_doc_extractor"),
     *      "reader" = @DI\Inject("annotation_reader"),
     *      "router" = @DI\Inject("router"),
     *      "storeManagerVoter" = @DI\Inject("lighthouse.core.security.voter.store_manager")
     * })
     */
    public function __construct(
        ApiDocExtractor $apiDocExtractor,
        Reader $reader,
        RouterInterface $router,
        StoreManagerVoter $storeManagerVoter
    ) {
        $this->apiDocExtractor = $apiDocExtractor;
        $this->reader = $reader;
        $this->router = $router;
        $this->storeManagerVoter = $storeManagerVoter;
    }

    /**
     * @return array
     */
    public function extractAll()
    {
        $routes = $this->router->getRouteCollection();
        $extraction = $this->apiDocExtractor->extractAnnotations($routes->all());

        $resources = array();
        foreach ($extraction as $extract) {
            $resourceName = $this->normalizeResourceName($extract['resource']);
            $verb = $this->getMethodVerb($extract['annotation'], $extract['resource']);
            $resources[$resourceName][$verb] = $this->parseAnnotation($extract['annotation']);
        }

        return $resources;
    }

    /**
     * @param SecurityContextInterface $securityContext
     * @return array
     */
    public function extractForCurrentUser(SecurityContextInterface $securityContext)
    {
        $resources = $this->extractAll();
        $userPermissions = array();
        foreach ($resources as $resourceName => $methods) {
            if ('other' == $resourceName) {
                continue;
            }
            $userPermissions[$resourceName] = array();
            foreach ($methods as $method => $roles) {
                if (true === $roles || $securityContext->isGranted($roles)) {
                    $userPermissions[$resourceName][] = $method;
                }
            }
        }
        return $userPermissions;
    }

    /**
     * @param string $resourceName
     * @return string
     */
    protected function normalizeResourceName($resourceName)
    {
        if (0 === strpos($resourceName, $this->restPrefix)) {
            return substr($resourceName, strlen($this->restPrefix));
        } else {
            return $resourceName;
        }
    }

    /**
     * @param ApiDoc $apiDoc
     * @return bool|array
     */
    protected function parseAnnotation(ApiDoc $apiDoc)
    {
        $controller = $apiDoc->getRoute()->getDefault('_controller');
        $method = $this->apiDocExtractor->getReflectionMethod($controller);
        $secure = $this->reader->getMethodAnnotation($method, 'JMS\\SecurityExtraBundle\\Annotation\\Secure');
        $roles = ($secure) ? $secure->roles : true;
        // Parse SecureParams Annotation
        $secureParam = $this->reader->getMethodAnnotation(
            $method,
            'JMS\\SecurityExtraBundle\\Annotation\\SecureParam'
        );
        if ($secureParam) {
            foreach ($secureParam->permissions as $attribute) {
                if ($this->storeManagerVoter->supportsAttribute($attribute)) {
                    if (true === $roles) {
                        $roles = array();
                    }
                    $roles[] = $this->storeManagerVoter->getRoleByAttribute($attribute);
                }
            }
        }

        return $roles;
    }

    /**
     * @param ApiDoc $apiDoc
     * @param $rawResourceName
     * @return string
     */
    protected function getMethodVerb(ApiDoc $apiDoc, $rawResourceName)
    {
        $httpMethod = $apiDoc->getRoute()->getRequirement('_method');
        $path = $apiDoc->getRoute()->getPath();
        $verb = str_replace(array($rawResourceName, $this->restSuffix), array('', ''), $path);
        $verb = ltrim($verb, '/');
        $methodVerb = $httpMethod;
        if ($verb) {
            $methodVerb.= '::' . $verb;
        }
        return $methodVerb;
    }
}
