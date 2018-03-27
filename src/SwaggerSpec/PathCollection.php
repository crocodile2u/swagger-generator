<?php

namespace SwaggerGenerator\SwaggerSpec;

use SwaggerGenerator\Integration\PathCollectionInterface;
use SwaggerGenerator\Integration\PathInterface;

class PathCollection implements PathCollectionInterface
{
    /**
     * @var PathInterface[]
     */
    private $paths = [];

    /**
     * @inheritdoc
     */
    public function add(PathInterface $path)
    {
        $uri = $path->getUri();
        if (array_key_exists($uri, $this->paths)) {
            throw new \InvalidArgumentException("Path {$uri} already exists in this path collection");
        }
        $this->paths[$uri] = $path;
        return $this;
    }

    /**
     * @param PathCollectionInterface $collection
     * @throws \InvalidArgumentException
     */
    public function merge(PathCollectionInterface $collection)
    {
        foreach ($collection->asArray() as $path) {
            $this->add($path);
        }
    }

    /**
     * @inheritdoc
     */
    public function asArray()
    {
        return $this->paths;
    }

    public function jsonSerialize()
    {
        return $this->asArray();
    }
}