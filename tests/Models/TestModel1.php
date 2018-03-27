<?php

namespace Tests\SwaggerGenerator\Models;

use SwaggerGenerator\Integration\ReferenceResolver;
use SwaggerGenerator\Integration\SerializationContext;
use SwaggerGenerator\SwaggerSpec\Type\Obj;
use SwaggerGenerator\SwaggerSpec\Type\Ref;

class TestModel1 implements ReferenceResolver
{
    /**
     * @return Obj
     */
    public static function resolveSwaggerType(SerializationContext $context)
    {
        return (new Obj())->addProperty("ref", new Ref($context, "Test", TestModel::class));
    }
}