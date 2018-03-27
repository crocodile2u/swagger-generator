<?php

namespace Tests\SwaggerGenerator\SwaggerSpec\Type;

use PHPUnit\Framework\TestCase;
use SwaggerGenerator\SwaggerSpec\Type;

class StrTest extends TestCase
{
    function testSimpleString()
    {
        $str = Type::string();
        $json = $str->jsonSerialize();
        $this->assertEquals(["type" => "string"], $json);
    }

    function testPattern()
    {
        $regexp = "/[a-z]+/";
        $str = Type::string()->setPattern($regexp);
        $json = $str->jsonSerialize();
        $this->assertEquals(["type" => "string", "pattern" => $regexp], $json);
    }
}