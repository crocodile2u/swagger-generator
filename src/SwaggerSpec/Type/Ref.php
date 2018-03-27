<?php

namespace SwaggerGenerator\SwaggerSpec\Type;

use SwaggerGenerator\Integration\Reference;
use SwaggerGenerator\Integration\ReferenceResolver;
use SwaggerGenerator\Integration\SerializationContext;
use SwaggerGenerator\Integration\TypeInterface;
use SwaggerGenerator\SwaggerSpec\Type;

class Ref extends Type implements Reference
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $resolverClass;
    /**
     * @var SerializationContext
     */
    private $context;

    /**
     * Object constructor.
     * @param string|null $name
     */
    public function __construct(SerializationContext $context, $name, $resolverClass)
    {
        parent::__construct(self::REF);
        $this->context = $context;
        $this->name = $name;
        $this->setResolverClassName($resolverClass);
        $context->registerReference($this);
    }

    private function setResolverClassName($class)
    {
        if (!class_implements($class, ReferenceResolver::class)) {
            throw new \LogicException("$class should implement " . ReferenceResolver::class);
        }
        $this->resolverClass = $class;
    }

    public function getResolverClassName()
    {
        return $this->resolverClass;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function resolveSwaggerType()
    {
        return call_user_func([$this->getResolverClassName(), "resolveSwaggerType"], $this->context);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            '$ref' => "#/definitions/{$this->name}",
        ];
    }
}