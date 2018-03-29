<?php

namespace SwaggerGenerator\Generator;

use SwaggerGenerator\SwaggerSpec;

class ControllerList
{
    /**
     * @var string[]
     */
    private $classes;
    /**
     * @var SwaggerSpec\Schema
     */
    private $schema;

    /**
     * ControllerList constructor.
     * @param string[] $classes
     */
    public function __construct(array $classes, SwaggerSpec\Schema $schema)
    {
        $this->classes = $classes;
        $this->schema = $schema;
    }

    /**
     * @return SwaggerSpec
     */
    public function generate()
    {
        $accumulativePathCollection = new SwaggerSpec\PathCollection();
        foreach ($this->classes as $controller) {
            $paths = call_user_func([$controller, "getSwaggerPaths"], $this->schema);
            $accumulativePathCollection->addCollection($paths, $this->schema);
        }

        return new SwaggerSpec($accumulativePathCollection, $this->schema);
    }
}