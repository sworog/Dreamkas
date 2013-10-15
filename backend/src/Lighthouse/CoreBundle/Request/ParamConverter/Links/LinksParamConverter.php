<?php

namespace Lighthouse\CoreBundle\Request\ParamConverter\Links;

use Lighthouse\CoreBundle\Response\DocumentResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @DI\Service("lighthouse.core.converter.links")
 * @DI\Tag("request.param_converter", attributes={"converter": "links"})
 */
class LinksParamConverter implements ParamConverterInterface
{
    /**
     * @var HttpKernelInterface
     */
    protected $kernel;

    /**
     * @DI\InjectParams({
     *      "kernel" = @DI\Inject("http_kernel")
     * })
     * @param HttpKernelInterface $kernel
     */
    public function __construct(HttpKernelInterface $kernel = null)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param Request $request
     * @param ConfigurationInterface|ParamConverter $configuration
     * @return bool
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function apply(Request $request, ConfigurationInterface $configuration)
    {
        if (!$request->headers->has('Link')) {
            throw new BadRequestHttpException('Link header is required');
        }

        $links = new Links();
        $linkHeader = $request->headers->get('Link');
        $linkHeaders = explode(',', $linkHeader);
        foreach ($linkHeaders as $linkHeader) {
            $link = $this->parseLinkHeaders($linkHeader);
            $this->resolveResource($link, $request);
            $links->add($link);
        }

        $request->attributes->set($configuration->getName(), $links);

        return true;
    }

    /**
     * @param string $linkHeader
     * @return Link
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    protected function parseLinkHeaders($linkHeader)
    {
        $matches = null;
        if (!preg_match('/^\s*<(.+?)>;\s*rel\s*=\s*"?(.+)"?\s*$/', $linkHeader, $matches)) {
            throw new BadRequestHttpException(sprintf('Invalid Link header provided: %s', $linkHeader));
        }

        $rel = trim($matches[2], '" ');
        $resourceUri = $matches[1];

        return new Link($rel, $resourceUri);
    }

    protected function resolveResource(Link $link, Request $request)
    {
        $subRequest = $this->createSubRequest($link, $request);
        try {
            $response = $this->kernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);
        } catch (\Exception $e) {
            throw new BadRequestHttpException(sprintf('Failed to fetch resource: %s', $link->getResourceUri()));
        }

        if ($response instanceof DocumentResponse) {
            $resource = $response->getDocument();
            $link->setResource($resource);
        }
    }

    /**
     * @param Link $link
     * @param Request $request
     * @return Request
     */
    protected function createSubRequest(Link $link, Request $request)
    {
        $subRequest = Request::create($link->getResourceUri(), 'GET');
        $subRequest->query->set('_format', 'json');
        if ($request->headers->has('Authorization')) {
            $subRequest->headers->set('Authorization', $request->headers->get('Authorization'));
        }
        return $subRequest;
    }

    /**
     * @param ConfigurationInterface|ParamConverter $configuration
     * @return boolean
     */
    public function supports(ConfigurationInterface $configuration)
    {
        return 'Lighthouse\\CoreBundle\\Request\\ParamConverter\\Links' == $configuration->getClass();
    }
}
