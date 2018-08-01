<?php

namespace SwaggerGenerator\SwaggerSpec\SecurityDefinition;

use SwaggerGenerator\SwaggerSpec\SecurityDefinition;

class ApiKey extends SecurityDefinition
{
    const IN_HEADER = "header",
        IN_QUERY = "query",
        HEADER_NAME_AUTHORIZATION = "Autorization",
        HEADER_NAME_KEY = "X-Api-Key";
    /**
     * @var string
     */
    private $in;
    /**
     * @var string
     */
    private $name;

    /**
     * ApiKey constructor.
     * @param string $in
     * @param string $name
     */
    public function __construct(string $in = self::IN_HEADER, string $name = self::HEADER_NAME_AUTHORIZATION)
    {
        parent::__construct("apiKey");
        $this->in = $in;
        $this->name = $name;
    }

    public function jsonSerialize()
    {
        return parent::jsonSerialize() +
            [
                "in" => $this->in,
                "name" => $this->name
            ];
    }
}