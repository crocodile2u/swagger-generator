<?php

namespace Tests\SwaggerGenerator\SwaggerSpec;

use PHPUnit\Framework\TestCase;
use SwaggerGenerator\SwaggerSpec\Schema;
use SwaggerGenerator\SwaggerSpec\Type;

class TypeTest extends TestCase
{
    /**
     * @param array $spec
     * @param Type $expected
     * @dataProvider providerTestFromArray
     */
    public function testFromArray($spec, $expected)
    {
        $scalar = Type::fromArray($spec, new Schema());
        $this->assertEquals($expected, $scalar);
    }

    public function providerTestFromArray()
    {
        return [
            [
                ["type" => "integer"],
                Type::int()
            ],
            [
                ["type" => "number"],
                Type::number()
            ],
            [
                ["type" => "boolean"],
                Type::bool()
            ],
            [
                ["type" => "string"],
                Type::string()
            ],
            [
                ["type" => "string", "format" => "date"],
                Type::string("date")
            ],
            [
                ["type" => "object", "properties" => ["id" => ["type" => "integer"]]],
                Type::object()->addProperty("id", Type::int())
            ],
        ];
    }
}