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
     * Object constructor.
     * @param string|null $name
     */
    public function __construct(SerializationContext $context, $name)
    {
        parent::__construct(self::REF);
        $this->name = basename($name);
        $context->registerReference($this);
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            "schema" => [
                '$ref' => "#/definitions/{$this->name}"
            ]
        ];
    }
}