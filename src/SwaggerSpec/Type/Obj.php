<?php

namespace SwaggerGenerator\SwaggerSpec\Type;

use SwaggerGenerator\Integration\SerializationContext;
use SwaggerGenerator\Integration\SerializationContextInterface;
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
     * @param array $spec
     * @return Type|Obj
     */
    public static function fromArray(array $spec, SerializationContextInterface $context)
    {
        if (empty($spec["properties"]) || !is_array($spec["properties"])) {
            throw new \InvalidArgumentException("Obj::fromArray() expects properties key to be an array");
        }
        $required = empty($spec["required"]) ? [] : $spec["required"];
        if (!is_array($required)) {
            throw new \InvalidArgumentException("Obj::fromArray() expects required key to be an array or to be missing");
        }
        $ret = new self;
        foreach ($spec["properties"] as $name => $propertySpec) {
            $isRequired = in_array($name, $required);
            $propertyType = Type::fromSpec($propertySpec, $context);
            $ret->addProperty($name, $propertyType, $isRequired);
        }
        return $ret;
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