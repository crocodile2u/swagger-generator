<?php

namespace Tests\SwaggerGenerator\SwaggerSpec\Type;

use PHPUnit\Framework\TestCase;
use SwaggerGenerator\SwaggerSpec\Schema;
use SwaggerGenerator\SwaggerSpec\Type;
use SwaggerGenerator\SwaggerSpec\Type\Scalar;

class ScalarTest extends TestCase
{
    /**
     * @param array $spec
     * @param Scalar $expected
     * @dataProvider providerTestFromArray
     */
    public function testFromArray($spec, $expected)
    {
        $scalar = Scalar::fromArray($spec, new Schema());
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
        ];
    }

    /**
     * @param string $type
     * @param string|null $format
     * @param array $rules
     * @param array $expectedResult
     * @dataProvider providerTestJsonSerialize
     */
    function testJsonSerialize($type, $format = null, array $rules = [], array $expectedResult)
    {
        $type = new Scalar($type, $format);
        foreach ($rules as $name => $value) {
            $type->addRule($name, $value);
        }
        $serialized = $type->jsonSerialize();
        $this->assertEquals($expectedResult, $serialized);
    }

    function providerTestJsonSerialize()
    {
        return [
            "integer" => [
                Scalar::INTEGER, null, [], ["type" => Scalar::INTEGER]
            ],
            "int32" => [
                Scalar::INTEGER, Scalar::INT32, [], ["type" => Scalar::INTEGER, "format" => Scalar::INT32]
            ],
            "int64" => [
                Scalar::INTEGER, Scalar::INT64, [], ["type" => Scalar::INTEGER, "format" => Scalar::INT64]
            ],
            "float" => [
                Scalar::NUMBER, Scalar::FLOAT, [], ["type" => Scalar::NUMBER, "format" => Scalar::FLOAT]
            ],
            "with rules" => [
                Scalar::INTEGER, null, ["minimum" => 50], ["type" => Scalar::INTEGER, "minimum" => 50]
            ]
        ];
    }
}