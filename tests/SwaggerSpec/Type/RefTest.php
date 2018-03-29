<?php

namespace Tests\SwaggerGenerator\SwaggerSpec\Type;

use PHPUnit\Framework\TestCase;
use SwaggerGenerator\SwaggerSpec\Schema;
use SwaggerGenerator\SwaggerSpec\Type\Ref;
use Tests\SwaggerGenerator\ReferenceResolver\TestModel;
use Tests\SwaggerGenerator\ReferenceResolver\TestModel1;
use Tests\SwaggerGenerator\ReferenceResolver\TestResolver;

class RefTest extends TestCase
{
    public function testRegisteringInSerializationContext()
    {
        $schema = new Schema();
        $schema->registerResolver(new TestResolver());
        $ref = new Ref($schema, "Test");
        $json = json_encode($ref);
        $decoded = json_decode($json, true);
        $this->assertInternalType("array", $decoded);

        $this->assertSchemaHasTestModelType($schema);
    }
    public function testRegisteringInSerializationContextRecursive()
    {
        $schema = new Schema();
        $schema->registerResolver(new TestResolver());
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
                "schema" => [
                    "\$ref" => "#/definitions/Test"
                ]
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