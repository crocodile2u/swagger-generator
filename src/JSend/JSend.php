<?php

namespace SwaggerGenerator\JSend;

use SwaggerGenerator\SwaggerSpec\Type;
use SwaggerGenerator\SwaggerSpec\Type\Obj;

class JSend extends Obj
{
    /**
     * JSend constructor.
     * @param Type $dataType
     */
    public function __construct(Type $dataType = null)
    {
        parent::__construct();

        $status = Type::string();
        $status->setPattern("/^success|fail|error$/");
        parent::addProperty("status", $status, true);
        if ($dataType) {
            parent::addProperty("data", $dataType, false);
        }
        parent::addProperty("message", Type::string(), false);
        parent::addProperty("code", Type::int(), false);
    }

    /**
     * @inheritdoc
     */
    public final function addProperty($name, Type $type, $required = false)
    {
        throw new \LogicException("Cannot add properties to JSend type after initialization");
    }

    public final function jsonSerialize()
    {
        return parent::jsonSerialize();
    }
}