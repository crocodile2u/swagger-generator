<?php

namespace SwaggerGenerator\SwaggerSpec;

use SwaggerGenerator\Integration\Reference;
use SwaggerGenerator\Integration\ReferenceResolver;
use SwaggerGenerator\Integration\SerializationContext;

class Schema implements SerializationContext, \JsonSerializable
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
     * @var ReferenceResolver[]
     */
    private $resolvers = [];

    /**
     * @param string $name
     * @param string $resolverClass
     */
    public function registerReference(Reference $ref)
    {
        if (!in_array($ref->getTypeName(), $this->unresolved)) {
            $this->unresolved[] = $ref->getTypeName();
        }
    }

    /**
     * @param ReferenceResolver $resolver
     */
    public function registerResolver(ReferenceResolver $resolver)
    {
        $this->resolvers[] = $resolver;
    }

    public function jsonSerialize()
    {
        $this->resolveReferences();
        return $this->resolved;
    }

    private function resolveReferences()
    {
        while (count($this->unresolved)) {
            $typeName = array_shift($this->unresolved);
            if (array_key_exists($typeName, $this->resolved)) {
                continue;
            }
            foreach ($this->resolvers as $resolver) {
                $type = $resolver->resolveSwaggerType($this, $typeName);
                if ($type) {
                    $this->resolved[$typeName] = $type;
                    break;
                }
            }
            if (empty($this->resolved[$typeName])) {
                throw new \LogicException("Can't resolve reference to $typeName");
            }
        }
    }
}