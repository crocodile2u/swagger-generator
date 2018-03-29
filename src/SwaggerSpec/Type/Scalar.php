<?php

namespace SwaggerGenerator\SwaggerSpec\Type;

use SwaggerGenerator\Integration\SerializationContext;
use SwaggerGenerator\SwaggerSpec\Type;

class Scalar extends Type
{
    const INTEGER = "integer";
    const NUMBER = "number";
    const BOOLEAN = "boolean";
    const STRING = "string";

    const FLOAT = "float";
    const DOUBLE = "double";
    const INT32 = "int32";
    const INT64 = "int64";

    /**
     * @param array $spec
     * @return self
     */
    public static function fromArray(array $spec, SerializationContext $context)
    {
        if (self::STRING === $spec["type"]) {
            $instance = new Str();
        } else {
            $instance = new Scalar($spec["type"]);
        }
        unset($spec["type"]);
        foreach ($spec as $ruleName => $rule) {
            $instance->addRule($ruleName, $rule);
        }
        return $instance;
    }
}