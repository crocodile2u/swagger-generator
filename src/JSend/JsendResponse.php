<?php

namespace SwaggerGenerator\JSend;

use SwaggerGenerator\SwaggerSpec\Response;
use SwaggerGenerator\SwaggerSpec\Type;

class JsendResponse extends Response
{
    /**
     * Response constructor.
     * @param Type $type
     * @param string $description
     */
    final function __construct(Type $dataType, $description = "")
    {
        parent::__construct(new JSend($dataType), $description);
    }
}