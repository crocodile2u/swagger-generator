<?php

namespace SwaggerGenerator\SwaggerSpec;

use SwaggerGenerator\Integration\EndpointInterface;
use SwaggerGenerator\Integration\ParameterInterface;
use SwaggerGenerator\Integration\ResponseInterface;

class Endpoint implements EndpointInterface
{
    /**
     * @var string
     */
    private $summary = "";
    /**
     * @var string
     */
    private $description = "";
    /**
     * @var string
     */
    private $operationId;
    /**
     * @var string[]
     */
    private $produces = ["application/json"];
    /**
     * @var Response[]
     */
    private $responses = [];
    /**
     * @var Parameter[]
     */
    private $parameters = [];

    /**
     * @param string $summary
     * @return $this
     */
    public function setSummary($summary): Endpoint
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description): Endpoint
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $operationId
     * @return $this
     */
    public function setOperationId($operationId): Endpoint
    {
        $this->operationId = $operationId;
        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function addResponseContentType($type)
    {
        $this->produces[] = $type;
        return $this;
    }

    /**
     * @param string[] $types
     * @return $this
     */
    public function replaceResponseContentTypes(array $types)
    {
        $this->produces = $types;
        return $this;
    }

    /**
     * @param Parameter[] $parameters
     * @return $this
     */
    public function setParameters(array $parameters): Endpoint
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @param Parameter[] $parameters
     * @param bool $override
     * @return $this
     */
    public function mergeParameters(array $parameters, $override)
    {
        foreach ($parameters as $parameter) {
            [$existing, $index] = $this->findParameter($parameter);
            if ($existing && $override) {
                $this->parameters[$index] = $parameter;
            } elseif (!$existing) {
                $this->addParameter($parameter);
            }
        }
        return $this;
    }

    /**
     * @param Parameter $parameter
     * @return $this
     */
    public function addParameter(ParameterInterface $parameter)
    {
        $this->parameters[] = $parameter;
        return $this;
    }

    /**
     * @param string|Parameter $search
     * @return array [int, Parameter] | [null, null]
     */
    public function findParameter($search)
    {
        if ($search instanceof Parameter) {
            $search = $search->getName();
        }
        foreach ($this->parameters as $index => $parameter) {
            if ($parameter->getName() === $search) {
                return [$parameter, $index];
            }
        }
        return [null, null];
    }

    /**
     * @param string $code
     * @param ResponseInterface $response
     * @return $this
     */
    public function addResponse($code, ResponseInterface $response)
    {
        $this->responses[$code] = $response;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            "summary" => $this->summary,
            "description" => $this->description,
            "operationId" => (string) $this->operationId,
            "parameters" => $this->parameters,
            "produces" => $this->produces,
            "responses" => $this->responses,
        ];
    }
}