<?php

namespace Tests\SwaggerGenerator\SwaggerSpec\Type;

use PHPUnit\Framework\TestCase;
use SwaggerGenerator\SwaggerSpec\Schema;
use SwaggerGenerator\SwaggerSpec\Type;
use Tests\SwaggerGenerator\Models\TestModel;

class TypedArrayTest extends TestCase
{
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
        $str = new Type\TypedArray(new Type\Ref($context, "Test", TestModel::class));
        $json = json_decode(json_encode($str), true);
        $expected = [
            "type" => "array",
            "items" => ['$ref' => '#/definitions/Test']
        ];
        $this->assertEquals($expected, $json);
    }
}