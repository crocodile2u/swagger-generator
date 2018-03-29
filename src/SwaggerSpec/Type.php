<?php

namespace SwaggerGenerator\SwaggerSpec;

use SwaggerGenerator\Integration\SerializationContext;
use SwaggerGenerator\SwaggerSpec\Type\Obj;
use SwaggerGenerator\SwaggerSpec\Type\Ref;
use SwaggerGenerator\SwaggerSpec\Type\Scalar;
use SwaggerGenerator\SwaggerSpec\Type\Str;
use SwaggerGenerator\SwaggerSpec\Type\TypedArray;

abstract class Type implements \JsonSerializable
{
    const OBJECT = "object";
    const REF = "ref";
    const ARRAYTYPE = "array";

    /**
     * @param Type|array $spec
     */
    public static function fromSpec($spec, SerializationContext $context)
    {
        return $spec instanceof self ? $spec : self::fromArray($spec, $context);
    }

    /**
     * @param array $spec
     * @return self
     */
    public static function fromArray(array $spec, SerializationContext $context)
    {
        if (isset($spec["schema"])) {
            $ref = $spec["schema"];
            if ($ref instanceof Ref) {
                return $ref;
            } elseif (is_string($ref)) {
                return new Ref($context, $ref);
            } else {
                throw new \InvalidArgumentException("schema key must contain a valid Type\\Ref object");
            }
        }
        $type = empty($spec["type"]) ? null : $spec["type"];
        switch ($type) {
            case Scalar::STRING:
            case Scalar::INTEGER:
            case Scalar::NUMBER:
            case Scalar::BOOLEAN:
                return Scalar::fromArray($spec, $context);
                break;
            case self::ARRAYTYPE:
                return TypedArray::fromArray($spec, $context);
                break;
            case self::OBJECT:
                return Obj::fromArray($spec, $context);
                break;
            default:
                throw new \InvalidArgumentException("Unexpected swagger type $type");
        }
    }

    /**
     * @param string $type
     * @param string|null $format
     * @return Scalar
     * @throws \InvalidArgumentException
     */
    public static function createScalar($type, $format = null)
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
    public static function int($format = null)
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
    public static function number($format = null)
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
    public static function string($format = null)
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

    public static function object()
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

    public function __construct($type, $format = null)
    {
        $this->type = $type;
        $this->addRule("format", $format);
    }

    /**
     * @param bool $flag
     */
    public function setNullable($flag)
    {
        return $this->addRule("nullable", $flag);
    }

    /**
     * @param string $name
     * @param string|int|bool $value
     * @return $this
     */
    public function addRule($name, $value)
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