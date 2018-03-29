<?php

namespace Tests\SwaggerGenerator\Generator;

use PHPUnit\Framework\TestCase;
use SwaggerGenerator\Generator\DirectoryScanner;
use SwaggerGenerator\SwaggerSpec\Schema;
use Tests\SwaggerGenerator\ReferenceResolver\TestResolver;

class DirectoryScannerTest extends TestCase
{
    public function testScan()
    {
        $schema = new Schema();
        $schema->registerResolver(new TestResolver());
        $generator = new DirectoryScanner(__DIR__ . "/../", $schema);
        $spec = $generator->scan();

        $spec->setHost("test.host")
            ->setBasePath("/base/path")
            ->setInfoTitle("Test title")
            ->setInfoDescription("Test description")
            ->setInfoContactEmail("test@email")
            ->setInfoLicenseName("Test license")
            ->setInfoLicenseUrl("https://test.license.url/")
            ->setInfoTermsOfServiceUrl("https://test.terms.of.service/")
            ->setInfoVersion("1.0");

        $json = json_decode(json_encode($spec), true);

        $expected = include __DIR__ . "/expected-spec.php";

        $this->assertEquals($expected, $json);

        $spec->setKeepEmptyValues(false);
        $spec->setInfoTitle(null);
        $json = json_decode(json_encode($spec), true);
        $this->assertArrayNotHasKey("title", $json["info"]);
    }
}