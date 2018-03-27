<?php

namespace SwaggerGenerator\JSend;

use SwaggerGenerator\SwaggerSpec\Type;
use SwaggerGenerator\SwaggerSpec\Type\Obj;

class JSend extends Obj
{
    /**
     * @var bool
     */
    private $frozen = false;

    /**
     * JSend constructor.
     * @param Type $dataType
     */
    public function __construct(Type $dataType = null)
    {
        parent::__construct();

        $status = Type::string();
        $status->setPattern("/^success|fail|error$/");
        $this->addProperty("status", $status, true);
        if ($dataType) {
            $this->addProperty("data", $dataType, false);
        }
        $this->addProperty("message", Type::string(), false);
        $this->addProperty("code", Type::int(), false);
        $this->frozen = true;
    }

    /**
     * @inheritdoc
     */
    public final function addProperty($name, Type $type, $required = false)
    {
        if ($this->frozen) {
            throw new \LogicException("Cannot add properties to JSend type after initialization");
        }
        return parent::addProperty($name, $type, $required);
    }

    public final function jsonSerialize()
    {
        return parent::jsonSerialize();
    }
}