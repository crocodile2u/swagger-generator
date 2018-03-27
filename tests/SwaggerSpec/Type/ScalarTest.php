<?php

namespace Tests\SwaggerGenerator\SwaggerSpec\Type;

use PHPUnit\Framework\TestCase;
use SwaggerGenerator\SwaggerSpec\Type\Scalar;

class ScalarTest extends TestCase
{
    /**
     * @param string $type
     * @param string|null $format
     * @param array $rules
     * @param array $expectedResult
     * @dataProvider providerTestJsonSerialize
     */
    function testJsonSerialize(string $type, string $format = null, array $rules = [], array $expectedResult)
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