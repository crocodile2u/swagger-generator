<?php

namespace Tests\SwaggerGenerator\Controller;

use SwaggerGenerator\Integration\Controller;
use SwaggerGenerator\Integration\SerializationContext;

class CallableControllerSpecAsArray implements Controller
{
    public function __invoke()
    {

    }

    public static function getSwaggerPaths(SerializationContext $context)
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
                            "schema" => "Test"
                        ]
                    ],
                    "produces" => "application/json",
                    "responses" => [
                        200 => [
                            "description" => "",
                            "type" => "integer"
                        ]
                    ]
                ]
            ]
        ];
    }
}