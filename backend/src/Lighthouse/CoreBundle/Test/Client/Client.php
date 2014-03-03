<?php

namespace Lighthouse\CoreBundle\Test\Client;

use Symfony\Bundle\FrameworkBundle\Client as BaseClient;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use Closure;

class Client extends BaseClient
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var bool
     */
    private $hasPerformedRequest = false;

    /**
     * @var bool
     */
    private $profiler = false;

    /**
     * @var Closure[]
     */
    protected $tweakers = array();

    /**
     * @param string $uri
     * @param string $content
     * @param string $type
     * @return null|\Symfony\Component\DomCrawler\Crawler
     */
    protected function createCrawlerFromContent($uri, $content, $type)
    {
        return null;
    }

    /**
     * @param JsonRequest $jsonRequest
     * @param \stdClass|string $accessToken
     * @return array
     */
    public function jsonRequest(JsonRequest $jsonRequest, $accessToken = null)
    {
        if (null !== $accessToken) {
            $jsonRequest->setAccessToken($accessToken);
        }

        $this->request(
            $jsonRequest->method,
            $jsonRequest->uri,
            $jsonRequest->parameters,
            $jsonRequest->files,
            $jsonRequest->server,
            $jsonRequest->content,
            $jsonRequest->changeHistory
        );

        return $this->getJsonResponse();
    }

    /**
     * @return mixed
     * @throws \RuntimeException
     */
    public function getJsonResponse()
    {
        $content = $this->getResponse()->getContent();
        $json = json_decode($content, true);

        if (0 != json_last_error()) {
            throw new \RuntimeException(
                sprintf('Failed asserting that response body is json. Response given: %s', $content)
            );
        }

        return $json;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function doRequest($request)
    {
        if ($this->hasPerformedRequest) {
            $this->kernel->shutdown();
        } else {
            $this->hasPerformedRequest = true;
        }

        $this->tweakContainer();

        if ($this->profiler) {
            $this->profiler = false;

            $this->kernel->boot();
            $this->kernel->getContainer()->get('profiler')->enable();
        }

        $response = $this->kernel->handle($request, HttpKernelInterface::MASTER_REQUEST, true);

        if ($this->kernel instanceof TerminableInterface) {
            $this->kernel->terminate($request, $response);
        }

        return $response;
    }

    protected function tweakContainer()
    {
        $this->kernel->boot();
        foreach ($this->tweakers as $tweaker) {
            $tweaker($this->getContainer());
        }
        $this->tweakers = array();
    }

    /**
     * @param callable $tweaker
     */
    public function addTweaker(Closure $tweaker)
    {
        $this->tweakers[] = $tweaker;
    }
}
