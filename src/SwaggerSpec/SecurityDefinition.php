<?php

namespace SwaggerGenerator\SwaggerSpec;

abstract class SecurityDefinition implements \JsonSerializable
{
    /**
     * @var string
     */
    private $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function jsonSerialize()
    {
        return [
            "type" => $this->type
        ];
    }
}