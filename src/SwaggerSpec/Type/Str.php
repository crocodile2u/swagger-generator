<?php

namespace SwaggerGenerator\SwaggerSpec\Type;

class Str extends Scalar
{
    const DATE = "date";
    const DATETIME = "date-time";
    const PASSWORD = "password";
    const BYTE = "byte";
    const BINARY = "binary";

    public function __construct(string $format = null)
    {
        parent::__construct(self::STRING, $format);
    }

    /**
     * @return Str
     */
    public function setPattern(string $regexp)
    {
        return $this->addRule("pattern", $regexp);
    }
}