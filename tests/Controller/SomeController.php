<?php

namespace Tests\SwaggerGenerator\Controller;

use SwaggerGenerator\Integration\Controller;
use SwaggerGenerator\Integration\SerializationContext;
use SwaggerGenerator\SwaggerSpec\Endpoint;
use SwaggerGenerator\SwaggerSpec\Parameter;
use SwaggerGenerator\SwaggerSpec\Path;
use SwaggerGenerator\SwaggerSpec\PathCollection;
use SwaggerGenerator\SwaggerSpec\Response;
use SwaggerGenerator\SwaggerSpec\Type;
use Tests\SwaggerGenerator\Models\TestModel1;

class SomeController implements Controller
{
    public static function getSwaggerPaths(SerializationContext $context)
    {
        $paths = new PathCollection();
        $paths->add(
            (new Path("/multiaction/{id}"))
                ->addEndpoint(
                    "get",
                    (new Endpoint())->addParameter(
                        new Parameter("id", "path", Type::int())
                    )->addParameter(
                        new Parameter("obj", "body", new Type\Ref($context, "Test1", TestModel1::class))
                    )->addResponse(200, new Response(
                        new Type\Ref($context, "Test1", TestModel1::class)
                    ))
                )
        );
        return $paths;
    }

    public function someAction()
    {

    }
}