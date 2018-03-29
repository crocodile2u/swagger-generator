<?php

namespace SwaggerGenerator\SwaggerSpec;

use SwaggerGenerator\Integration\EndpointInterface;
use SwaggerGenerator\Integration\PathInterface;

class Path implements PathInterface
{
    private $endpoints = [];
    /**
     * @var string
     */
    private $uri;

    /**
     * Path constructor.
     * @param string $uri
     */
    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    public function addEndpointsArray(array $endpoints)
    {
        foreach ($endpoints as $httpVerb => $endpointSpec) {
            $endpoint = $endpointSpec instanceof EndpointInterface
                ? $endpointSpec
                : Endpoint::fromArray();
        }
    }

    /**
     * @param string $httpVerb
     * @param EndpointInterface $endpoint
     * @return PathInterface
     */
    public function addEndpoint($httpVerb, EndpointInterface $endpoint)
    {
        if (array_key_exists($httpVerb, $this->endpoints)) {
            throw new \InvalidArgumentException("{$httpVerb} HTTP verb already is set for this path");
        }
        $this->endpoints[$httpVerb] = $endpoint;
        return $this;
    }

    public function getUri()
    {
        return$this->uri;
    }

    public function jsonSerialize()
    {
        return $this->endpoints;
    }
}