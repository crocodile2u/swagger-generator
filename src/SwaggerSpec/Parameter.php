<?php

namespace SwaggerGenerator\SwaggerSpec;

use SwaggerGenerator\Integration\ParameterInterface;
use SwaggerGenerator\Integration\SerializationContext;

class Parameter implements ParameterInterface
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $in;
    /**
     * @var Type
     */
    private $type;
    /**
     * @var bool
     */
    private $required;
    /**
     * @var string
     */
    private $description;

    /**
     * @param $spec
     * @return Parameter
     */
    public static function fromSpec($spec, SerializationContext $context)
    {
        return $spec instanceof self ? $spec : self::fromArray($spec, $context);
    }

    /**
     * @param array $spec
     * @return Parameter
     */
    public static function fromArray(array $spec, SerializationContext $context)
    {
        $required = !empty($spec["required"]);
        return new self($spec["name"], $spec["in"], Type::fromArray($spec, $context), $required);
    }

    /**
     * Parameter constructor.
     * @param string $name
     * @param string $in
     * @param Type $type
     * @param bool $required
     */
    public function __construct($name, $in, Type $type, $required = true)
    {
        $this->name = $name;
        $this->in = $in;
        $this->type = $type;
        $this->required = $required;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function jsonSerialize()
    {
        $ret = [
            "in" => $this->in,
            "name" => $this->name,
            "required" => $this->required,
        ];
        $ret += $this->type->jsonSerialize();
        return $ret;
    }
}