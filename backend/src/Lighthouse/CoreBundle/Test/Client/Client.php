<?php

namespace Lighthouse\CoreBundle\Test\Client;

use Symfony\Bundle\FrameworkBundle\Client as BaseClient;

class Client extends BaseClient
{
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
}
