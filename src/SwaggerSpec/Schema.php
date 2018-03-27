<?php

namespace SwaggerGenerator\SwaggerSpec;

use SwaggerGenerator\Integration\Reference;
use SwaggerGenerator\Integration\SerializationContext;

class Schema implements SerializationContext, \JsonSerializable
{
    /**
     * @var array[]
     */
    private $types = [];

    /**
     * @param string $name
     * @param string $resolverClass
     */
    public function registerReference(Reference $ref)
    {
        $name = $ref->getName();
        $class = $ref->getResolverClassName();
        if (!array_key_exists($name, $this->types)) {
            $object = $ref->resolveSwaggerType();
            $this->types[$name] = [$class, $object];
        } elseif ($ref->getResolverClassName() !== $this->types[$ref->getName()][0]) {
            $message = "Reference to {$ref->getName()} is ambiguous, definitions provided by both " .
                "{$this->types[$ref->getName()][0]} and {$class}";
            throw new \LogicException($message);
        }
    }

    public function jsonSerialize()
    {
        $export = [];
        foreach ($this->types as $name => $spec) {
            $export[$name] = $spec[1];
        }
        return $export;
    }
}