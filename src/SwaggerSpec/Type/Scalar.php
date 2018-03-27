<?php

namespace SwaggerGenerator\SwaggerSpec\Type;

use SwaggerGenerator\SwaggerSpec\Type;

class Scalar extends Type
{
    const INTEGER = "integer";
    const NUMBER = "number";
    const BOOLEAN = "boolean";
    const STRING = "string";

    const FLOAT = "float";
    const DOUBLE = "double";
    const INT32 = "int32";
    const INT64 = "int64";
}