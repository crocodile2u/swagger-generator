<?php

namespace Tests\SwaggerGenerator\SwaggerSpec\Type;

use PHPUnit\Framework\TestCase;
use SwaggerGenerator\SwaggerSpec\Schema;
use SwaggerGenerator\SwaggerSpec\Type;

class TypedArrayTest extends TestCase
{
    /**
     * @param array $spec
     * @param Type $expected
     * @dataProvider providerTestFromArray
     */
    public function testFromArray($spec, $expected)
    {
        $scalar = Type\TypedArray::fromArray($spec, new Schema());
        $this->assertEquals($expected, $scalar);
    }

    public function providerTestFromArray()
    {
        return [
            [
                ["type" => "array", "items" => ["type" => "integer"]],
                Type::arrayOf(Type::int())
            ],
        ];
    }
    function testSimpleIntArray()
    {
        $str = new Type\TypedArray(Type::int());
        $json = json_decode(json_encode($str), true);
        $expected = [
            "type" => "array",
            "items" => ["type" => "integer"]
        ];
        $this->assertEquals($expected, $json);
    }
    function testArrayOfNamedObjects()
    {
        $context = new Schema();
        $str = new Type\TypedArray(new Type\Ref($context, "Test"));
        $json = json_decode(json_encode($str), true);
        $expected = [
            "type" => "array",
            "items" => [
                'schema' => [
                    '$ref' => '#/definitions/Test'
                ]
            ]
        ];
        $this->assertEquals($expected, $json);
    }
}