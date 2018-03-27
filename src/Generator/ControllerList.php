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
     * ControllerList constructor.
     * @param string[] $classes
     */
    public function __construct(array $classes)
    {
        $this->classes = $classes;
    }

    /**
     * @return SwaggerSpec
     */
    public function generate()
    {
        $schema = new SwaggerSpec\Schema();

        $accumulativePathCollection = new SwaggerSpec\PathCollection();
        foreach ($this->classes as $controller) {
            $paths = call_user_func([$controller, "getSwaggerPaths"], $schema);
            $accumulativePathCollection->merge($paths);
        }

        return new SwaggerSpec($accumulativePathCollection, $schema);
    }
}