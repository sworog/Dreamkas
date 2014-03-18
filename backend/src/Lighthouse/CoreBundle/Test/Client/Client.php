<?php

namespace Lighthouse\CoreBundle\Test\Client;

use Symfony\Bundle\FrameworkBundle\Client as BaseClient;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use Closure;
use Symfony\Component\Process\PhpProcess;

class Client extends BaseClient
{
    /**
     * @var KernelInterface|TerminableInterface
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
     *
     */
    public function shutdownKernelBeforeRequest()
    {
        $this->hasPerformedRequest = true;
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
        return $this->decodeJsonResponse($this->getResponse());
    }

    /**
     * @param Response $response
     * @return mixed
     * @throws \RuntimeException
     */
    public function decodeJsonResponse(Response $response)
    {
        $content = $response->getContent();
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

    /**
     * @param JsonRequest $jsonRequest
     * @return PhpProcess
     */
    public function createProcessRequest(JsonRequest $jsonRequest)
    {
        $uri = $this->getAbsoluteUri($jsonRequest->uri);
        $server = array_merge($this->server, $jsonRequest->server);

        if (!$this->history->isEmpty()) {
            $server['HTTP_REFERER'] = $this->history->current()->getUri();
        }

        $server['HTTP_HOST'] = parse_url($jsonRequest->uri, PHP_URL_HOST);

        if ($port = parse_url($uri, PHP_URL_PORT)) {
            $server['HTTP_HOST'] .= ':'.$port;
        }

        $server['HTTPS'] = 'https' == parse_url($jsonRequest->uri, PHP_URL_SCHEME);

        $internalRequest = new Request(
            $uri,
            $jsonRequest->method,
            $jsonRequest->parameters,
            $jsonRequest->files,
            $this->cookieJar->allValues($uri),
            $server,
            $jsonRequest->content
        );

        if (true === $jsonRequest->changeHistory) {
            $this->history->add($internalRequest);
        }

        $request = $this->filterRequest($internalRequest);

        $process = new PhpProcess(
            $this->getScript($request),
            null,
            array('TMPDIR' => sys_get_temp_dir(), 'TEMP' => sys_get_temp_dir())
        );

        return $process;
    }

    /**
     * @param JsonRequest $jsonRequest
     * @param int $times
     * @return Response[]
     */
    public function parallelJsonRequest(JsonRequest $jsonRequest, $times = 2)
    {
        /* @var PhpProcess[] $processes */
        $processes = array();
        for ($i = 0; $i < $times; $i++) {
            $processes[] = $this->createProcessRequest($jsonRequest);
        }
        foreach ($processes as $process) {
            $process->start();
        }
        /* @var Response[] $responses */
        $responses = array();
        foreach ($processes as $process) {
            $process->wait();
            $responses[] = $this->parseProcess($process);
        }
        return $responses;
    }

    /**
     * @param PhpProcess $process
     * @return mixed
     * @throws \RuntimeException
     */
    public function parseProcess(PhpProcess $process)
    {
        if (!$process->isSuccessful() || !preg_match('/^O\:\d+\:/', $process->getOutput())) {
            throw new \RuntimeException(
                sprintf('OUTPUT: %s ERROR OUTPUT: %s', $process->getOutput(), $process->getErrorOutput())
            );
        }

        return unserialize($process->getOutput());
    }
}
