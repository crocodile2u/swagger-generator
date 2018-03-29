<?php

namespace SwaggerGenerator\SwaggerSpec\Type;

use SwaggerGenerator\Integration\SerializationContext;
use SwaggerGenerator\SwaggerSpec\Type;

class TypedArray extends Type
{
    /**
     * @param array $spec
     * @return self
     */
    public static function fromArray(array $spec, SerializationContext $context)
    {
        $itemsTypeSpec = empty($spec["items"]) ? null : $spec["items"];
        if (empty($itemsTypeSpec)) {
            throw new \InvalidArgumentException("Array type must contain items key with item type specification");
        }
        return new self(Type::fromSpec($itemsTypeSpec, $context));
    }

    public function __construct(Type $type)
    {
        parent::__construct(self::ARRAYTYPE);
        $this->addRule("items", $type);
    }
}