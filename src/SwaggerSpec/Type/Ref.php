<?php

namespace SwaggerGenerator\SwaggerSpec\Type;

use SwaggerGenerator\Integration\ReferenceInterface;
use SwaggerGenerator\Integration\SerializationContextInterface;
use SwaggerGenerator\SwaggerSpec\Type;

class Ref extends Type implements ReferenceInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * Object constructor.
     * @param string|null $name
     */
    public function __construct(SerializationContextInterface $context, $name)
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