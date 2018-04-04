<?php

namespace SwaggerGenerator\SwaggerSpec;

use SwaggerGenerator\Integration\EndpointInterface;
use SwaggerGenerator\Integration\ParameterInterface;
use SwaggerGenerator\Integration\ResponseInterface;
use SwaggerGenerator\Integration\SerializationContext;

class Endpoint implements EndpointInterface
{
    private $defaultContentTypes = ["application/json"];
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
    private $produces = [];
    /**
     * @var string[]
     */
    private $consumes = [];
    /**
     * @var Response[]
     */
    private $responses = [];
    /**
     * @var Parameter[]
     */
    private $parameters = [];

    /**
     * @param $spec
     * @return Endpoint
     */
    public static function fromSpec($spec, SerializationContext $context)
    {
        return $spec instanceof self ? $spec : self::fromArray($spec, $context);
    }

    /**
     * @param array $spec
     * @return Endpoint
     */
    public static function fromArray(array $spec, SerializationContext $context)
    {
        $ret = new self;
        foreach ($spec as $key => $value) {
            switch ($key) {
                case "summary":
                    $ret->setSummary($value);
                    break;
                case "description":
                    $ret->setDescription($value);
                    break;
                case "operationId":
                    $ret->setOperationId($value);
                    break;
                case "produces":
                    foreach ((array) $value as $contentType) {
                        $ret->addResponseContentType($contentType);
                    }
                    break;
                case "consumes":
                    foreach ((array) $value as $contentType) {
                        $ret->addRequestContentType($contentType);
                    }
                    break;
                case "responses":
                    if (!is_array($value)) {
                        throw new \InvalidArgumentException("Endpoint.responses is expected to be an array( HTTP Status Code => Response Spec,.. )");
                    }
                    foreach ($value as $statusCode => $responseSpec) {
                        $response = Response::fromSpec($responseSpec, $context);
                        $ret->addResponse($statusCode, $response);
                    }
                    break;
                case "parameters":
                    if (!is_array($value)) {
                        throw new \InvalidArgumentException("Endpoint.parameters is expected to be an array( Parameter Spec 1,.. )");
                    }
                    foreach ($value as $parameterSpec) {
                        $ret->addParameter(Parameter::fromSpec($parameterSpec, $context));
                    }
                    break;
                default:
                    throw new \InvalidArgumentException("Endpoint specification contains unexpected key $key");
            }
        }
        return $ret;
    }

    /**
     * @param string $summary
     * @return $this
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $operationId
     * @return $this
     */
    public function setOperationId($operationId)
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
        $this->produces = array_unique($this->produces);
        return $this;
    }

    public function addRequestContentType($type)
    {
        $this->consumes[] = $type;
        $this->consumes = array_unique($this->consumes);
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
     * @param string[] $types
     * @return $this
     */
    public function replaceRequestContentTypes(array $types)
    {
        $this->consumes = $types;
        return $this;
    }

    /**
     * @param Parameter[] $parameters
     * @return $this
     */
    public function setParameters(array $parameters)
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
            list($existing, $index) = $this->findParameter($parameter);
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
        if ("formData" === $parameter->locatedIn()) {
            $this->addRequestContentType("application/x-www-form-urlencoded");
        }
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
            "produces" => $this->produces ?: $this->defaultContentTypes,
            "consumes" => $this->consumes ?: $this->defaultContentTypes,
            "responses" => $this->responses,
        ];
    }
}