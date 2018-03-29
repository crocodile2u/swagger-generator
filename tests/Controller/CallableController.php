<?php

namespace Tests\SwaggerGenerator\Controller;

use SwaggerGenerator\Integration\Controller;
use SwaggerGenerator\Integration\PathCollectionInterface;
use SwaggerGenerator\Integration\SerializationContext;
use SwaggerGenerator\SwaggerSpec\Endpoint;
use SwaggerGenerator\SwaggerSpec\Parameter;
use SwaggerGenerator\SwaggerSpec\Path;
use SwaggerGenerator\SwaggerSpec\PathCollection;
use SwaggerGenerator\SwaggerSpec\Response;
use SwaggerGenerator\SwaggerSpec\Type;
use Tests\SwaggerGenerator\ReferenceResolver\TestModel;

class CallableController implements Controller
{
    public function __invoke()
    {

    }

    public static function getSwaggerPaths(SerializationContext $context)
    {
        $paths = new PathCollection();
        $paths->add(
            (new Path("/callable/{id}"))
                ->addEndpoint(
                    "get",
                    (new Endpoint())->addParameter(
                        new Parameter("id", "path", Type::int())
                    )->addParameter(
                        new Parameter("objParam", "body", new Type\Ref($context, "Test"))
                    )->addResponse(200, new Response(
                        new Type\Ref($context, "Test")
                    ))
                )
        );
        return $paths;
    }
}