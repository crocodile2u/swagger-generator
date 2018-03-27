<?php

namespace Tests\SwaggerGenerator\SwaggerSpec\Type;

use PHPUnit\Framework\TestCase;
use SwaggerGenerator\SwaggerSpec\Schema;
use SwaggerGenerator\SwaggerSpec\Type\Ref;
use Tests\SwaggerGenerator\Models\TestModel;
use Tests\SwaggerGenerator\Models\TestModel1;

class RefTest extends TestCase
{
    public function testRegisteringInSerializationContext()
    {
        $schema = new Schema();
        $ref = new Ref($schema, "Test", TestModel::class);
        $json = json_encode($ref);
        $decoded = json_decode($json, true);
        $this->assertInternalType("array", $decoded);

        $this->assertSchemaHasTestModelType($schema);
    }
    public function testRegisteringInSerializationContextRecursive()
    {
        $schema = new Schema();
        $ref = new Ref($schema, "Test1", TestModel1::class);
        $json = json_encode($ref);
        $decoded = json_decode($json, true);
        $this->assertInternalType("array", $decoded);

        $this->assertSchemaHasTestModelType($schema);

        $json = json_encode($schema);
        $decoded = json_decode($json, true);
        $this->assertArrayHasKey("Test1", $decoded);
        $testSpec = $decoded["Test1"];
        $this->assertEquals("object", $testSpec["type"]);
        $this->assertEquals(["ref" => ["\$ref" => "#/definitions/Test"]], $testSpec["properties"]);
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