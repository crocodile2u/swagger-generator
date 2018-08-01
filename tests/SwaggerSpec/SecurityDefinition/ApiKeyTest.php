<?php

namespace Tests\SwaggerGenerator\SwaggerSpec\SecurityDefinition;

use PHPUnit\Framework\TestCase;
use SwaggerGenerator\SwaggerSpec\SecurityDefinition\ApiKey;

class ApiKeyTest extends TestCase
{
    /**
     * @param string $in
     * @param string $name
     * @param array $expected
     * @dataProvider providerJsonSerialize
     */
    public function testJsonSerialize($in, $name, $expected)
    {
        $def = new ApiKey($in, $name);
        $this->assertEquals($expected, $def->jsonSerialize());
    }

    public function providerJsonSerialize()
    {
        return [
            ["header", "Authorization", ["type" => "apiKey", "in" => "header", "name" => "Authorization"]],
            ["query", "key", ["type" => "apiKey", "in" => "query", "name" => "key"]],
        ];
    }
}