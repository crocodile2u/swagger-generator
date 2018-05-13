<?php

namespace Tests\SwaggerGenerator\ReferenceResolver;

use SwaggerGenerator\Integration\ReferenceResolverInterface;
use SwaggerGenerator\Integration\SerializationContextInterface;
use SwaggerGenerator\SwaggerSpec\Type;
use SwaggerGenerator\SwaggerSpec\Type\Obj;
use SwaggerGenerator\SwaggerSpec\Type\Ref;

class TestResolver implements ReferenceResolverInterface
{

    public function resolveSwaggerType(SerializationContextInterface $context, $name)
    {
        switch ($name) {
            case "Test1":
                return (new Obj())->addProperty("ref", new Ref($context, "Test"));
            case "Test":
                return (new Obj())->addProperty("id", Type::int());
            default:
                return null;
        }
    }
}