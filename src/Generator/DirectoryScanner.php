<?php

namespace SwaggerGenerator\Generator;

use SwaggerGenerator\Integration\Controller;
use SwaggerGenerator\SwaggerSpec;

class DirectoryScanner
{
    /**
     * @var string
     */
    private $directoryPath;

    /**
     * DirectoryScanner constructor.
     * @param string $directoryPath
     */
    public function __construct($directoryPath)
    {
        $this->directoryPath = $directoryPath;
    }

    /**
     * @return SwaggerSpec
     */
    public function scan()
    {
        $directoryIterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->directoryPath));

        /** @var \SplFileInfo[] $phpFilesIterator*/
        $phpFilesIterator = new \CallbackFilterIterator($directoryIterator, function(\SplFileInfo $fileInfo) {
            return 'php' === strtolower($fileInfo->getExtension());
        });

        foreach ($phpFilesIterator as $fileInfo) {
            include_once $fileInfo->getRealPath();
        }

        $controllers = array_filter(get_declared_classes(), function($class) {
            return in_array(Controller::class, class_implements($class));
        });

        return (new ControllerList($controllers))->generate();
    }
}