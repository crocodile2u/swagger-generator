<?php

namespace Tests\SwaggerGenerator\JSend;

use PHPUnit\Framework\TestCase;
use SwaggerGenerator\JSend\JSend;
use SwaggerGenerator\SwaggerSpec\Type;
use SwaggerGenerator\SwaggerSpec\Type\Obj;

class JsendTest extends TestCase
{
    function testJsendWithEmptyDataType()
    {
        $type = new JSend();

        $expected = [
            'type' => 'object',
            'properties' => [
                'status' => [
                    'type' => 'string',
                    'pattern' => '/^success|fail|error$/',
                ],
                'message' => [
                    'type' => 'string',
                ],
                'code' => [
                    'type' => 'integer',
                ],
            ],
            'required' => ['status'],
        ];

        $this->assertEquals($expected, json_decode(json_encode($type), true));
    }
    function testJsendTypeSpec()
    {
        $innerType = new Obj();
        $innerType->addProperty("test", Type::int());

        $type = new JSend($innerType);

        $expected = [
            'type' => 'object',
            'properties' => [
                'status' => [
                    'type' => 'string',
                    'pattern' => '/^success|fail|error$/',
                ],
                'data' => [
                    'type' => 'object',
                    'properties' => [
                        'test' => [
                            'type' => 'integer',
                        ],
                    ],
                ],
                'message' => [
                    'type' => 'string',
                ],
                'code' => [
                    'type' => 'integer',
                ],
            ],
            'required' => ['status'],
        ];

        $this->assertEquals($expected, json_decode(json_encode($type), true));
    }
}