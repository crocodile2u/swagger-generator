<?php

namespace Tests\SwaggerGenerator\SwaggerSpec\Type;

use PHPUnit\Framework\TestCase;
use SwaggerGenerator\SwaggerSpec\Type;
use SwaggerGenerator\SwaggerSpec\Type\Obj;

class ObjectTest extends TestCase
{
    function testEmptyObject()
    {
        $type = new Obj();
        $this->assertEquals(
            ["type" => Type::OBJECT, "properties" => []],
            $type->jsonSerialize()
        );
    }

    /**
     * @param string $name
     * @param bool $required
     * @param array $expectedRequired
     * @dataProvider providerTestObjectWithProperty
     */
    function testObjectWithProperty(string $name, bool $required, array $expectedRequired)
    {
        $type = new Obj();
        $type->addProperty($name, Type::bool(), $required);
        $result = json_decode(json_encode($type), true);
        $expected = [
            "type" => Type::OBJECT,
            "properties" => ["test" => ["type" => Type\Scalar::BOOLEAN]],
        ];
        if ($expectedRequired) {
            $expected["required"] = $expectedRequired;
        }
        $this->assertEquals($expected, $result);
    }

    function providerTestObjectWithProperty()
    {
        return [
            ["test", false, []],
            ["test", true, ["test"]],
        ];
    }

    function testNamelessObjectsNestedSerialization()
    {
        $type = new Obj();

        $nested = new Obj();
        $type->addProperty("test", $nested);
        $result = json_decode(json_encode($type), true);
        $this->assertEquals(
            [
                "type" => Type::OBJECT,
                "properties" => [
                    "test" => [
                        "type" => Type\Scalar::OBJECT,
                        "properties" => [],
                    ]
                ],
            ],
            $result
        );
    }
}