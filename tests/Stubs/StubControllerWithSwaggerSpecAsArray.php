<?php

namespace Tests\SwaggerGenerator\Stubs;

use SwaggerGenerator\Integration\SerializationContextInterface;
use SwaggerGenerator\Integration\SwaggerServerInterface;

class StubControllerWithSwaggerSpecAsArray implements SwaggerServerInterface
{
    public static function getSwaggerPaths(SerializationContextInterface $context)
    {
        return [
            "/controller2/{id}" => [
                "get" => [
                    "parameters" => [
                        [
                            "in" => "path",
                            "name" => "id",
                            "required" => true,
                            "type" => "integer",
                        ],
                        [
                            "in" => "query",
                            "name" => "something",
                            "schema" => [
                                '$ref' => "Test1"
                            ]
                        ]
                    ],
                    "produces" => "application/json",
                    "responses" => [
                        200 => [
                            "description" => "",
                            "schema" => [
                                "type" => "integer"
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}