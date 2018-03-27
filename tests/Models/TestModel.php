<?php

namespace Tests\SwaggerGenerator\Models;

use SwaggerGenerator\Integration\ReferenceResolver;
use SwaggerGenerator\Integration\SerializationContext;
use SwaggerGenerator\SwaggerSpec\Type;
use SwaggerGenerator\SwaggerSpec\Type\Obj;

class TestModel implements ReferenceResolver
{
    /**
     * @return Obj
     */
    public static function resolveSwaggerType(SerializationContext $context)
    {
        return (new Obj())->addProperty("id", Type::int());
    }
}