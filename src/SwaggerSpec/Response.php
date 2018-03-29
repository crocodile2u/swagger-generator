<?php

namespace SwaggerGenerator\SwaggerSpec;

use SwaggerGenerator\Integration\ResponseInterface;
use SwaggerGenerator\Integration\SerializationContext;

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
     * @param $spec
     * @return Response
     */
    public static function fromSpec($spec, SerializationContext $context)
    {
        return $spec instanceof self ? $spec : self::fromArray($spec, $context);
    }

    public static function fromArray(array $spec, SerializationContext $context)
    {
        $decription = empty($spec["decription"]) ? "" : $spec["decription"];
        return new self(Type::fromSpec($spec, $context), $decription);
    }

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
        ] + $this->type->jsonSerialize();
    }
}