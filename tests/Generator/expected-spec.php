<?php

return [
    'swagger' => '2.0',
    'info' => [
        'title' => 'Test title',
        'description' => 'Test description',
        'version' => '1.0',
        'termsOfService' => 'https://test.terms.of.service/',
        'contact' => [
            'email' => 'test@email',
        ],
        'license' => [
            'name' => 'Test license',
            'url' => 'https://test.license.url/',
        ],
    ],
    'host' => 'test.host',
    'basePath' => '/base/path',
    'tags' => [],
    'schemes' => [],
    'paths' => [
        '/controller1/{id}' => [
            'get' => [
                'summary' => '',
                'description' => '',
                'operationId' => '',
                'parameters' => [
                    [
                        'in' => 'path',
                        'name' => 'id',
                        'required' => true,
                        'type' => 'integer',
                    ],
                    [
                        'in' => 'body',
                        'name' => 'objParam',
                        'required' => true,
                        'schema' => [
                            '$ref' => '#/definitions/Test',
                        ],
                    ],
                ],
                'produces' => [
                    'application/json',
                ],
                "consumes" => ["application/json"],
                'responses' => [
                    200 => [
                        'description' => '',
                        'schema' => [
                            '$ref' => '#/definitions/Test',
                        ],
                    ],
                ],
            ],
        ],
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
                        'in' => 'query',
                        'name' => 'something',
                        'required' => false,
                        'schema' => [
                            '$ref' => '#/definitions/Test1'
                        ]
                    ]
                ],
                "produces" => ["application/json"],
                "consumes" => ["application/json"],
                "responses" => [
                    200 => [
                        "description" => "",
                        "schema" => ["type" => "integer"]
                    ]
                ],
                'summary' => '',
                'description' => '',
                'operationId' => '',
            ]
        ]
    ],
    'definitions' => [
        'Test' => [
            'type' => 'object',
            'properties' => [
                'id' => [
                    'type' => 'integer',
                ],
            ],
        ],
        'Test1' => [
            'type' => 'object',
            'properties' => [
                'ref' => [
                    "schema" => [
                        '$ref' => '#/definitions/Test',
                    ]
                ],
            ],
        ],
    ],
];