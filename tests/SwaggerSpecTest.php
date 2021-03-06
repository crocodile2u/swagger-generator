<?php

namespace Tests\SwaggerGenerator;

use PHPUnit\Framework\TestCase;
use SwaggerGenerator\Integration\ReferenceResolverInterface;
use SwaggerGenerator\Integration\SerializationContextInterface;
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

        $resolver = new class implements ReferenceResolverInterface {
            public function resolveSwaggerType(SerializationContextInterface $context, $name)
            {
                $type = new SwaggerSpec\Type\Obj();
                $type->addProperty("id", SwaggerSpec\Type::int());
                return $type;
            }
        };
        $schema->registerReferenceResolver($resolver);

        $home = new SwaggerSpec\Endpoint();
        $homeResponseType = new SwaggerSpec\Type\Ref($schema, "HomeResponse");
        $home->addResponse(200, new SwaggerSpec\Response($homeResponseType));

        $pathHome = new SwaggerSpec\Path("/");
        $pathHome->addEndpoint("get", $home);

        $paths = new SwaggerSpec\PathCollection();
        $paths->add($pathHome);
        $spec = new SwaggerSpec($paths, $schema);
        $spec->setKeepEmptyValues(true);

        $spec->setInfoDescription("My cool API")
            ->setInfoLicenseName("MIT")
            ->setInfoLicenseUrl("https://opensource.org/licenses/MIT")
            ->setInfoVersion("1.0")
            ->setInfoContactEmail("me@example.com");

        $json = json_encode($spec);
        $decoded = json_decode($json, true);

        $expectedOutput = [
            "swagger" => "2.0",
            "info" => [
                'title' => null,
                'description' => "My cool API",
                'version' => "1.0",
                'termsOfService' => null,
                'contact' => [
                    'email' => "me@example.com",
                ],
                'license' => [
                    'name' => "MIT",
                    'url' => "https://opensource.org/licenses/MIT",
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
                        "consumes" => ["application/json"],
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