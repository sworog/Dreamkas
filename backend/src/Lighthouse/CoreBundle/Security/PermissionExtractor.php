<?php

namespace Lighthouse\CoreBundle\Security;

use Doctrine\Common\Annotations\Reader;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Security\Voter\StoreManagerVoter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Nelmio\ApiDocBundle\Extractor\ApiDocExtractor;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use ReflectionMethod;

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
            $resources[$resourceName][$verb] = $this->getRolesFromAnnotation($extract['annotation']);
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
     * @return bool|array array of roles or true if everyone have access to method
     */
    protected function getRolesFromAnnotation(ApiDoc $apiDoc)
    {
        $controller = $apiDoc->getRoute()->getDefault('_controller');
        $method = $this->apiDocExtractor->getReflectionMethod($controller);
        $roles = true;
        $roles = $this->mergeRoles($roles, $this->getRolesFromSecureAnnotation($method));
        $roles = $this->mergeRoles($roles, $this->getRolesFromSecureParamAnnotation($method));
        return $roles;
    }

    /**
     * @param bool|array $roles
     * @param bool|array $moreRoles
     * @return bool|array
     */
    protected function mergeRoles($roles, $moreRoles)
    {
        if (true === $roles) {
            return (true === $moreRoles) ? true : $moreRoles;
        } else {
            return (true === $moreRoles) ? $roles : array_merge($roles, $moreRoles);
        }
    }

    /**
     * @param \ReflectionMethod $method
     * @return array|bool
     */
    protected function getRolesFromSecureAnnotation(ReflectionMethod $method)
    {
        $secure = $this->reader->getMethodAnnotation(
            $method,
            'JMS\\SecurityExtraBundle\\Annotation\\Secure'
        );
        return ($secure) ? $secure->roles : true;
    }

    /**
     * @param ReflectionMethod $method
     * @return array|bool
     */
    protected function getRolesFromSecureParamAnnotation(ReflectionMethod $method)
    {
        // Parse SecureParams Annotation
        // @var SecureParam $secureParam
        $secureParam = $this->reader->getMethodAnnotation(
            $method,
            'JMS\\SecurityExtraBundle\\Annotation\\SecureParam'
        );
        return ($secureParam) ? $this->storeManagerVoter->getRolesByAttributes($secureParam->permissions) : true;
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
