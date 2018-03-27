<?php

namespace SwaggerGenerator\SwaggerSpec\Type;

use SwaggerGenerator\SwaggerSpec\Type;

class TypedArray extends Type
{
    public function __construct(Type $type)
    {
        parent::__construct(self::ARRAY);
        $this->addRule("items", $type);
    }
}