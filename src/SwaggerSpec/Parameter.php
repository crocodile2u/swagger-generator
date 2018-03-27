<?php

namespace SwaggerGenerator\SwaggerSpec;

use SwaggerGenerator\Integration\ParameterInterface;

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
        if ("body" === $this->in) {
            $ret["schema"] = $this->type;
        } else {
            $ret += $this->type->jsonSerialize();
        }
        return $ret;
    }
}