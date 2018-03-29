<?php

namespace SwaggerGenerator\SwaggerSpec;

use SwaggerGenerator\Integration\PathCollectionInterface;
use SwaggerGenerator\Integration\PathInterface;
use SwaggerGenerator\Integration\SerializationContext;

class PathCollection implements PathCollectionInterface
{
    /**
     * @var PathInterface[]
     */
    private $paths = [];

    /**
     * @param $spec
     * @return PathCollection
     */
    public static function fromSpec($spec, SerializationContext $context)
    {
        return $spec instanceof self ? $spec : self::fromArray($spec, $context);
    }

    /**
     * @param array $spec
     * @return PathCollection
     */
    public static function fromArray(array $spec, SerializationContext $context)
    {
        $collection = new self;
        foreach ($spec as $uri => $endpoints) {
            $path = new Path($uri);
            foreach ($endpoints as $httpVerb => $endpoint) {
                $path->addEndpoint($httpVerb, Endpoint::fromSpec($endpoint, $context));
            }
            $collection->add($path);
        }
        return $collection;
    }

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
     * @param PathCollectionInterface|array $collection
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function addCollection($collection, SerializationContext $context)
    {
        $paths = self::fromSpec($collection, $context);
        foreach ($paths->asArray() as $path) {
            $this->add($path);
        }
        return $this;
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