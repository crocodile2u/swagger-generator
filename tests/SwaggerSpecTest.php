<?php

namespace Tests\SwaggerGenerator;

use PHPUnit\Framework\TestCase;
use SwaggerGenerator\Integration\ReferenceResolver;
use SwaggerGenerator\Integration\SerializationContext;
use SwaggerGenerator\SwaggerSpec;
use SwaggerGenerator\SwaggerSpec\Schema;

class SwaggerSpecTest extends TestCase
{
    function testJsonSerializeCompact()
    {
        $schema = new Schema();
        $spec = new SwaggerSpec(new SwaggerSpec\PathCollection(), $schema);
        $spec->setKeepEmptyValues(false);
        $serializeOutput = $spec->jsonSerialize();
        $expectedOutput = [
            "swagger" => "2.0",
            "basePath" => "/",
            "definitions" => $schema,
        ];
        $this->assertEquals($expectedOutput, $serializeOutput);
    }
    function testJsonSerializeFull()
    {
        $schema = new Schema();
        $spec = new SwaggerSpec(new SwaggerSpec\PathCollection(), $schema);
        $spec->setKeepEmptyValues(true);
        $serializeOutput = $spec->jsonSerialize();
        $expectedOutput = [
            "swagger" => "2.0",
            "info" => [
                'title' => null,
                'description' => null,
                'version' => null,
                'termsOfService' => null,
                'contact' => [
                    'email' => null,
                ],
                'license' => [
                    'name' => null,
                    'url' => null,
                ],
            ],
            "host" => null,
            "tags" => [],
            "schemes" => [],
            "paths" => [],
            "basePath" => "/",
            "definitions" => $schema,
        ];
        $this->assertEquals($expectedOutput, $serializeOutput);
    }

    function testJsonSerializeFullWithData()
    {
        $schema = new Schema();

        $home = new SwaggerSpec\Endpoint();

        $homeResponse = new class implements ReferenceResolver {
            public static function resolveSwaggerType(SerializationContext $context)
            {
                $type = new SwaggerSpec\Type\Obj();
                $type->addProperty("id", SwaggerSpec\Type::int());
                return $type;
            }
        };
        $homeResponseType = new SwaggerSpec\Type\Ref($schema, "HomeResponse", get_class($homeResponse));
        $home->addResponse(200, new SwaggerSpec\Response($homeResponseType));

        $pathHome = new SwaggerSpec\Path("/");
        $pathHome->addEndpoint("get", $home);

        $paths = new SwaggerSpec\PathCollection();
        $paths->add($pathHome);
        $spec = new SwaggerSpec($paths, $schema);
        $spec->setKeepEmptyValues(true);

        $json = json_encode($spec);
        $decoded = json_decode($json, true);

        $expectedOutput = [
            "swagger" => "2.0",
            "info" => [
                'title' => null,
                'description' => null,
                'version' => null,
                'termsOfService' => null,
                'contact' => [
                    'email' => null,
                ],
                'license' => [
                    'name' => null,
                    'url' => null,
                ],
            ],
            "host" => null,
            "tags" => [],
            "schemes" => [],
            "paths" => [
                "/" => [
                    'get' => [
                        'summary' => '',
                        'description' => '',
                        'operationId' => '',
                        'parameters' => [],
                        'produces' => ['application/json'],
                        'responses' => [
                            200 => [
                                'description' => '',
                                'schema' => [
                                    '$ref' => '#/definitions/HomeResponse'
                                ]
                            ]
                        ],
                    ],
                ]
            ],
            "basePath" => "/",
            "definitions" => [
                'HomeResponse' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => [
                            'type' => 'integer',
                        ]
                    ]
                ]
            ],
        ];
        $this->assertEquals($expectedOutput, $decoded);
    }
}