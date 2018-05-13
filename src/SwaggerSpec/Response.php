<?php

namespace SwaggerGenerator\SwaggerSpec;

use SwaggerGenerator\Integration\ReferenceInterface;
use SwaggerGenerator\Integration\ResponseInterface;
use SwaggerGenerator\Integration\SerializationContextInterface;

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
    public static function fromSpec($spec, SerializationContextInterface $context)
    {
        return $spec instanceof self ? $spec : self::fromArray($spec, $context);
    }

    public static function fromArray(array $spec, SerializationContextInterface $context)
    {
        $decription = empty($spec["decsription"]) ? "" : $spec["description"];
        $schema = empty($spec["schema"]) ? [] : $spec["schema"];
        return new self(Type::fromSpec($schema, $context), $decription);
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
        if ($this->type instanceof ReferenceInterface) {
            $schemaJson = $this->type->jsonSerialize();
        } else {
            $schemaJson = [
                "schema" => $this->type->jsonSerialize()
            ];
        }
        return [
            "description" => $this->description
        ] + $schemaJson;
    }
}