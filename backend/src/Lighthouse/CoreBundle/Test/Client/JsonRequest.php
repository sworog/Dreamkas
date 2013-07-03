<?php

namespace Lighthouse\CoreBundle\Test\Client;

class JsonRequest
{
    /**
     * @var string
     */
    public $method = 'GET';

    /**
     * @var string
     */
    public $uri;

    /**
     * @var array
     */
    public $parameters = array();

    /**
     * @var array
     */
    public $files = array();

    /**
     * @var array
     */
    public $server = array();

    /**
     * @var string
     */
    public $content;

    /**
     * @var bool
     */
    public $changeHistory = true;

    /**
     * @param string $uri
     * @param string $method
     * @param array $data
     */
    public function __construct($uri, $method = 'GET', array $data = null)
    {
        $this->uri = $uri;
        $this->method = $method;

        $this->setJsonData($data);
        $this->setJsonHeaders();
    }

    /**
     *
     */
    public function setJsonHeaders()
    {
        if (!isset($this->server['CONTENT_TYPE'])) {
            $this->server['CONTENT_TYPE'] = 'application/json';
        }
        if (!isset($this->server['HTTP_ACCEPT'])) {
            $this->server['HTTP_ACCEPT'] = 'application/json, */*; q=0.01';
        }
    }

    /**
     * @param array $data
     */
    public function setJsonData(array $data = null)
    {
        if (null !== $data) {
            $this->content = json_encode($data);
        } else {
            $this->content = null;
        }
    }

    /**
     * @param \stdClass|string $accessToken
     */
    public function setAccessToken($accessToken)
    {
        if ($accessToken instanceof \stdClass) {
            $accessToken = $accessToken->access_token;
        }

        $this->addHttpHeader('Authorization', 'Bearer ' . $accessToken);
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function addHttpHeader($name, $value)
    {
        $header = 'HTTP_' . strtoupper($name);
        $this->server[$header] = $value;
    }
}
