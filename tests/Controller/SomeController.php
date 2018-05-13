<?php

namespace Tests\SwaggerGenerator\Controller;

use SwaggerGenerator\Integration\SerializationContextInterface;
use SwaggerGenerator\Integration\SwaggerServerInterface;
use SwaggerGenerator\SwaggerSpec\Endpoint;
use SwaggerGenerator\SwaggerSpec\Parameter;
use SwaggerGenerator\SwaggerSpec\Path;
use SwaggerGenerator\SwaggerSpec\PathCollection;
use SwaggerGenerator\SwaggerSpec\Response;
use SwaggerGenerator\SwaggerSpec\Type;

class SomeController implements SwaggerServerInterface
{
    public static function getSwaggerPaths(SerializationContextInterface $context)
    {
        $paths = new PathCollection();
        $paths->add(
            (new Path("/multiaction/{id}"))
                ->addEndpoint(
                    "get",
                    (new Endpoint())->addParameter(
                        new Parameter("id", "path", Type::int())
                    )->addParameter(
                        new Parameter("obj", "body", new Type\Ref($context, "Test1"))
                    )->addResponse(200, new Response(
                        new Type\Ref($context, "Test1")
                    ))
                )
        );
        return $paths;
    }

    public function someAction()
    {

    }
}