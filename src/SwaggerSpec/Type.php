<?php

namespace SwaggerGenerator\SwaggerSpec;

use SwaggerGenerator\SwaggerSpec\Type\Obj;
use SwaggerGenerator\SwaggerSpec\Type\Scalar;
use SwaggerGenerator\SwaggerSpec\Type\Str;
use SwaggerGenerator\SwaggerSpec\Type\TypedArray;

abstract class Type implements \JsonSerializable
{
    const OBJECT = "object";
    const REF = "ref";
    const ARRAY = "array";

    /**
     * @param string $type
     * @param string|null $format
     * @return Scalar
     * @throws \InvalidArgumentException
     */
    public static function createScalar(string $type, string $format = null): Scalar
    {
        switch ($type) {
            case Scalar::STRING:
                return self::string($format);
            case Scalar::INTEGER:
                return self::int($format);
            case Scalar::NUMBER:
                return self::number($format);
            case Scalar::BOOLEAN:
                return self::bool();
            default:
                throw new \InvalidArgumentException("Type $type is not recognized as a scalar type");
        }
    }

    /**
     * @param string|null $format
     * @return Scalar
     */
    public static function int(string $format = null)
    {
        return new Scalar(Scalar::INTEGER, $format);
    }

    /**
     * @return Scalar
     */
    public static function int32()
    {
        return new Scalar(Scalar::INTEGER, Scalar::INT32);
    }

    /**
     * @return Scalar
     */
    public static function int64()
    {
        return new Scalar(Scalar::INTEGER, Scalar::INT64);
    }

    /**
     * @param string|null $format
     * @return Scalar
     */
    public static function number(string $format = null)
    {
        return new Scalar(Scalar::NUMBER, $format);
    }

    /**
     * @return Scalar
     */
    public static function float()
    {
        return self::number(Scalar::FLOAT);
    }

    /**
     * @return Scalar
     */
    public static function double()
    {
        return self::number(Scalar::DOUBLE);
    }

    /**
     * @return Str
     */
    public static function string(string $format = null)
    {
        return new Str($format);
    }

    /**
     * @return Str
     */
    public static function date()
    {
        return new Str(Str::DATE);
    }

    /**
     * @return Str
     */
    public static function dateTime()
    {
        return new Str(Str::DATETIME);
    }

    /**
     * @return Str
     */
    public static function password()
    {
        return new Str(Str::PASSWORD);
    }

    /**
     * @return Str
     */
    public static function byte()
    {
        return new Str(Str::BYTE);
    }

    /**
     * @return Str
     */
    public static function binary()
    {
        return new Str(Str::BINARY);
    }

    /**
     * @return Scalar
     */
    public static function bool()
    {
        return new Scalar(Scalar::BOOLEAN);
    }

    /**
     * @param Type $type
     * @return TypedArray
     */
    public static function arrayOf(self $type)
    {
        return new TypedArray($type);
    }

    public static function object(): Obj
    {
        return new Obj();
    }

    /**
     * @var string
     */
    protected $type;
    /**
     * @var array
     */
    private $rules = [];

    public function __construct(string $type, string $format = null)
    {
        $this->type = $type;
        $this->addRule("format", $format);
    }

    /**
     * @param bool $flag
     */
    public function setNullable(bool $flag)
    {
        return $this->addRule("nullable", $flag);
    }

    /**
     * @param string $name
     * @param string|int|bool $value
     * @return $this
     */
    public function addRule(string $name, $value)
    {
        if (null !== $value) {
            $this->rules[$name] = $value;
        }
        return $this;
    }

    public function asJson()
    {
        $ret = json_encode($this);
        return $ret;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $ret = [
                "type" => $this->type
            ] + $this->rules;

        return $ret;
    }
}