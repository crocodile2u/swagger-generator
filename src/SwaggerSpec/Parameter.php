<?php

namespace SwaggerGenerator\SwaggerSpec;

use SwaggerGenerator\Integration\ParameterInterface;
use SwaggerGenerator\Integration\SerializationContextInterface;
use SwaggerGenerator\SwaggerSpec\Type\Ref;

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
    public static function fromSpec($spec, SerializationContextInterface $context)
    {
        return $spec instanceof self ? $spec : self::fromArray($spec, $context);
    }

    /**
     * @param array $spec
     * @return Parameter
     */
    public static function fromArray(array $spec, SerializationContextInterface $context)
    {
        $required = !empty($spec["required"]);
        $name = $spec["name"] ?? "";
        $in = $spec["in"] ?? "";
        unset($spec["name"], $spec["in"], $spec["required"]);
        $typeSpec = $spec["schema"] ?? $spec;
        return new self($name, $in, Type::fromArray($typeSpec, $context), $required);
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
     * @inheritdoc
     */
    public function locatedIn()
    {
        return $this->in;
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

        if ($this->type instanceof Ref) {
            $ret["schema"] = $this->type->jsonSerialize();
        } else {
            $ret += $this->type->jsonSerialize();
        }

        return $ret;
    }
}