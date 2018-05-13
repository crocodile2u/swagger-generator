<?php

namespace Tests\SwaggerGenerator\Stubs;

use SwaggerGenerator\Integration\SerializationContextInterface;
use SwaggerGenerator\Integration\SwaggerServerInterface;
use SwaggerGenerator\SwaggerSpec\Endpoint;
use SwaggerGenerator\SwaggerSpec\Parameter;
use SwaggerGenerator\SwaggerSpec\Path;
use SwaggerGenerator\SwaggerSpec\PathCollection;
use SwaggerGenerator\SwaggerSpec\Response;
use SwaggerGenerator\SwaggerSpec\Type;

class StubController implements SwaggerServerInterface
{
    public static function getSwaggerPaths(SerializationContextInterface $context)
    {
        $paths = new PathCollection();
        $paths->add(
            (new Path("/controller1/{id}"))
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