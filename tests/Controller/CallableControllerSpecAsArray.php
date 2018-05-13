<?php

namespace Tests\SwaggerGenerator\Controller;

use SwaggerGenerator\Integration\SerializationContextInterface;
use SwaggerGenerator\Integration\SwaggerServerInterface;

class CallableControllerSpecAsArray implements SwaggerServerInterface
{
    public function __invoke()
    {

    }

    public static function getSwaggerPaths(SerializationContextInterface $context)
    {
        return [
            "/some-path/{id}" => [
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
                                '$ref' => "Test"
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