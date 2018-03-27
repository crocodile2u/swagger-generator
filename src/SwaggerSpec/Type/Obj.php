<?php

namespace SwaggerGenerator\SwaggerSpec\Type;

use SwaggerGenerator\SwaggerSpec\Type;

class Obj extends Type
{
    /**
     * @var array
     */
    private $properties = [];
    /**
     * @var array
     */
    private $required = [];

    /**
     * Object constructor.
     * @param string|null $name
     */
    public function __construct()
    {
        parent::__construct(self::OBJECT);
    }

    /**
     * @param string $name
     * @param Type $type
     * @return $this
     */
    public function addProperty($name, Type $type, $required = false)
    {
        $this->properties[$name] = $type;
        if ($required) {
            $this->required[] = $name;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $ret = [
            "type" => $this->type,
            "properties" => $this->properties,
        ];

        if (count($this->required)) {
            $ret["required"] = $this->required;
        }

        return $ret;
    }

}