<?php

namespace Tests\SwaggerGenerator\SwaggerSpec\Type;

use PHPUnit\Framework\TestCase;
use SwaggerGenerator\SwaggerSpec\Schema;
use SwaggerGenerator\SwaggerSpec\Type\Ref;
use Tests\SwaggerGenerator\Stubs\StubReferenceResolver;

class RefTest extends TestCase
{
    public function testRegisteringInSerializationContext()
    {
        $schema = new Schema();
        $schema->registerReferenceResolver(new StubReferenceResolver());
        $ref = new Ref($schema, "Test");
        $json = json_encode($ref);
        $decoded = json_decode($json, true);
        $this->assertInternalType("array", $decoded);

        $this->assertSchemaHasTestModelType($schema);
    }
    public function testRegisteringInSerializationContextRecursive()
    {
        $schema = new Schema();
        $schema->registerReferenceResolver(new StubReferenceResolver());
        $ref = new Ref($schema, "Test1");
        $json = json_encode($ref);
        $decoded = json_decode($json, true);
        $this->assertInternalType("array", $decoded);

        $this->assertSchemaHasTestModelType($schema);

        $json = json_encode($schema);
        $decoded = json_decode($json, true);
        $this->assertArrayHasKey("Test1", $decoded);
        $testSpec = $decoded["Test1"];
        $this->assertEquals("object", $testSpec["type"]);
        $expected = [
            "ref" => [
                "\$ref" => "#/definitions/Test"
            ]
        ];
        $this->assertEquals($expected, $testSpec["properties"]);
    }
    protected function assertSchemaHasTestModelType(Schema $schema)
    {
        $json = json_encode($schema);
        $decoded = json_decode($json, true);
        $this->assertInternalType("array", $decoded);
        $this->assertArrayHasKey("Test", $decoded);
        $testSpec = $decoded["Test"];
        $this->assertEquals("object", $testSpec["type"]);
        $this->assertEquals(["id" => ["type" => "integer"]], $testSpec["properties"]);
    }
}