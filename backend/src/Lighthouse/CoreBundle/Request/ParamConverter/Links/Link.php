<?php

namespace Lighthouse\CoreBundle\Request\ParamConverter\Links;

class Link
{
    /**
     * @var string
     */
    protected $rel;

    /**
     * @var string
     */
    protected $resourceUri;

    /**
     * @var mixed
     */
    protected $resource;

    /**
     * @param string $rel
     * @param string $resourceUri
     */
    public function __construct($rel, $resourceUri)
    {
        $this->rel = $rel;
        $this->resourceUri = $resourceUri;
    }

    /**
     * @return string
     */
    public function getRel()
    {
        return $this->rel;
    }

    /**
     * @return string
     */
    public function getResourceUri()
    {
        return $this->resourceUri;
    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }
}
