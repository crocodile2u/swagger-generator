<?php

namespace SwaggerGenerator\SwaggerSpec;

use SwaggerGenerator\Integration\ReferenceInterface;
use SwaggerGenerator\Integration\ReferenceResolverInterface;
use SwaggerGenerator\Integration\SerializationContextInterface;

class Schema implements SerializationContextInterface, \JsonSerializable
{
    /**
     * @var string[]
     */
    private $unresolved = [];

    /**
     * @var Type[]
     */
    private $resolved = [];

    /**
     * @var ReferenceResolverInterface[]
     */
    private $resolvers = [];

    /**
     * @param string $name
     * @param string $resolverClass
     */
    public function registerReference(ReferenceInterface $ref)
    {
        if (!in_array($ref->getTypeName(), $this->unresolved)) {
            $this->unresolved[] = $ref->getTypeName();
        }
    }

    /**
     * @param ReferenceResolverInterface $resolver
     */
    public function registerReferenceResolver(ReferenceResolverInterface $resolver)
    {
        $this->resolvers[] = $resolver;
    }

    public function jsonSerialize()
    {
        $this->resolveReferences();
        return (object) $this->resolved;
    }

    private function resolveReferences()
    {
        while (count($this->unresolved)) {
            $typeName = array_shift($this->unresolved);
            if (array_key_exists($typeName, $this->resolved)) {
                continue;
            }
            $this->resolved[$typeName] = $this->resolveReference($typeName);
        }
    }

    private function resolveReference(string $typeName)
    {
        foreach ($this->resolvers as $resolver) {
            $type = $resolver->resolveSwaggerType($this, $typeName);
            if ($type) {
                return $type;
            }
        }
        throw new \LogicException("Can't resolve reference to $typeName");
    }
}