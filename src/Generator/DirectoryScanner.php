<?php

namespace SwaggerGenerator\Generator;

use SwaggerGenerator\Integration\SwaggerServerInterface;
use SwaggerGenerator\SwaggerSpec;

class DirectoryScanner
{
    /**
     * @var string[]
     */
    private $directories;
    /**
     * @var SwaggerSpec\Schema
     */
    private $schema;

    /**
     * DirectoryScanner constructor.
     * @param string $directories
     */
    public function __construct(SwaggerSpec\Schema $schema, ...$directories)
    {
        $this->directories = $directories;
        $this->schema = $schema;
    }

    /**
     * @return SwaggerSpec
     */
    public function scan()
    {
        foreach ($this->directories as $directory) {
            $this->scanDir($directory);
        }

        $controllers = array_filter(get_declared_classes(), function($class) {
            return in_array(SwaggerServerInterface::class, class_implements($class));
        });

        return (new ControllerList($controllers, $this->schema))->generate();
    }

    /**
     * @return SwaggerSpec
     */
    public function scanDir(string $path): void
    {
        $directoryIterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

        /** @var \SplFileInfo[] $phpFilesIterator*/
        $phpFilesIterator = new \CallbackFilterIterator($directoryIterator, function(\SplFileInfo $fileInfo) {
            return 'php' === strtolower($fileInfo->getExtension());
        });

        foreach ($phpFilesIterator as $fileInfo) {
            include_once $fileInfo->getRealPath();
        }
    }
}