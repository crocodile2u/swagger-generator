<?php

namespace SwaggerGenerator\SwaggerSpec;

use SwaggerGenerator\Integration\ResponseInterface;

class Response implements ResponseInterface
{
    /**
     * @var string
     */
    private $description;
    /**
     * @var Type
     */
    private $type;

    /**
     * Response constructor.
     * @param Type $type
     * @param string $description
     */
    function __construct(Type $type, $description = "")
    {
        $this->type = $type;
        $this->description = $description;
    }

    function jsonSerialize()
    {
        return [
            "description" => $this->description,
            "schema" => json_decode($this->type->asJson()),
        ];
    }
}