<?php

namespace SwaggerGenerator;

use SwaggerGenerator\Integration\PathCollectionInterface;
use SwaggerGenerator\Integration\PathInterface;
use SwaggerGenerator\SwaggerSpec\Schema;
use SwaggerGenerator\SwaggerSpec\SecurityDefinition;
use SwaggerGenerator\SwaggerSpec\Type;

class SwaggerSpec implements \JsonSerializable
{
    const SWAGGER_VERSION = "2.0";
    /**
     * @var PathCollectionInterface
     */
    private $paths;
    /**
     * @var Schema
     */
    private $schema;

    private $infoDescription;
    private $infoVersion;
    private $infoTitle;
    private $infoTermsOfServiceUrl;
    private $infoContactEmail;
    private $infoLicenseName;
    private $infoLicenseUrl;
    private $host;
    private $basePath;
    private $tags = [];
    private $schemes = [];
    private $keepEmptyValues = true;
    /**
     * @var SecurityDefinition[]
     */
    private $securityDefinitions = [];
    /**
     * @var string[]
     */
    private $security = [];

    /**
     * SwaggerSpec constructor.
     * @param array $pathSpecs
     */
    public function __construct(PathCollectionInterface $paths, Schema $schema)
    {
        $this->paths = $paths;
        $this->schema = $schema;
    }

    public function asJson()
    {
        return json_encode($this);
    }

    public function jsonSerialize()
    {
        $ret = [
            "swagger" => self::SWAGGER_VERSION,
            "info" => $this->jsonSerializeInfo(),
            "host" => $this->host,
            "basePath" => "/{$this->basePath}",
            "tags" => $this->tags,
            "schemes" => $this->schemes,
            "paths" => $this->trimBasePath($this->paths->asArray()),
            "definitions" => $this->schema,
        ];

        if ($this->securityDefinitions) {
            $ret["securityDefinitions"] = $this->securityDefinitions;
            if ($this->security) {
                $ret["security"] = array_map(function($name) {
                    return [$name => []];
                }, $this->security);
            }
        }

        return $this->filterOutput($ret);
    }

    protected function jsonSerializeInfo()
    {
        $info = [
            "title" => $this->infoTitle,
            "description" => $this->infoDescription,
            "version" => $this->infoVersion,
            "termsOfService" => $this->infoTermsOfServiceUrl,
            "contact" => $this->jsonSerializeContactInfo(),
            "license" => $this->jsonSerializeLicenseInfo()
        ];
        return $this->filterOutput($info);
    }

    protected function jsonSerializeContactInfo()
    {
        return $this->filterOutput([
            "email" => $this->infoContactEmail,
        ]);
    }

    protected function jsonSerializeLicenseInfo()
    {
        return $this->filterOutput([
            "name" => $this->infoLicenseName,
            "url" => $this->infoLicenseUrl,
        ]);
    }

    protected function filterOutput(array $input)
    {
        return $this->keepEmptyValues ? $input : array_filter($input);
    }

    /**
     * @param string $name
     * @param string|null $description
     * @param string|null $externalDocsDescription
     * @param string $externalDocsUrl
     * @return $this
     */
    public function addTag(
        string $name,
        string $description = null,
        string $externalDocsDescription = null,
        string $externalDocsUrl
    ) {
        $tag = ["name" => $name];
        if ($description) {
            $tag["description"] = $description;
        }
        if ($externalDocsUrl) {
            $externalDocs = ["url" => $externalDocsUrl];
            if ($externalDocsDescription) {
                $externalDocs["description"] = $externalDocsDescription;
            }
            $tag["externalDocs"] = $externalDocs;
        }
        $this->tags[] = $tag;
        return $this;
    }

    public function addScheme(string $scheme)
    {
        $this->schemes[] = $scheme;
    }

    /**
     * @param mixed $infoDescription
     * @return $this
     */
    public function setInfoDescription($infoDescription)
    {
        $this->infoDescription = $infoDescription;
        return $this;
    }

    /**
     * @param mixed $infoVersion
     * @return $this
     */
    public function setInfoVersion($infoVersion)
    {
        $this->infoVersion = $infoVersion;
        return $this;
    }

    /**
     * @param mixed $infoTitle
     * @return $this
     */
    public function setInfoTitle($infoTitle)
    {
        $this->infoTitle = $infoTitle;
        return $this;
    }

    /**
     * @param mixed $infoTermsOfServiceUrl
     * @return $this
     */
    public function setInfoTermsOfServiceUrl($infoTermsOfServiceUrl)
    {
        $this->infoTermsOfServiceUrl = $infoTermsOfServiceUrl;
        return $this;
    }

    /**
     * @param mixed $infoContactEmail
     * @return $this
     */
    public function setInfoContactEmail($infoContactEmail)
    {
        $this->infoContactEmail = $infoContactEmail;
        return $this;
    }

    /**
     * @param mixed $infoLicenseName
     * @return $this
     */
    public function setInfoLicenseName($infoLicenseName)
    {
        $this->infoLicenseName = $infoLicenseName;
        return $this;
    }

    /**
     * @param mixed $infoLicenseUrl
     * @return $this
     */
    public function setInfoLicenseUrl($infoLicenseUrl)
    {
        $this->infoLicenseUrl = $infoLicenseUrl;
        return $this;
    }

    /**
     * @param mixed $host
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @param mixed $basePath
     * @return $this
     */
    public function setBasePath($basePath)
    {
        $this->basePath = trim($basePath, "/");
        return $this;
    }

    /**
     * @param bool $keepEmptyValues
     * @return $this
     */
    public function setKeepEmptyValues(bool $keepEmptyValues): SwaggerSpec
    {
        $this->keepEmptyValues = $keepEmptyValues;
        return $this;
    }

    /**
     * @param string $name
     * @param SecurityDefinition $definition
     */
    public function addSecurityDefinition(string $name, SecurityDefinition $definition)
    {
        $this->securityDefinitions[$name] = $definition;
    }

    public function setDefaultSecurity(string ...$names)
    {
        $this->security = $names;
    }

    /**
     * @param PathInterface[] $paths
     * @return array
     */
    private function trimBasePath($paths)
    {
        if (empty($this->basePath)) {
            return $paths;
        }
        $len = strlen($this->basePath);
        $ret = [];
        foreach ($paths as $uri => $path) {
            if (0 === strpos($uri, $this->basePath)) {
                $pathWithoutBasePath = substr($uri, $len);
            } else {
                $pathWithoutBasePath = $uri;
            }
            $pathWithoutBasePath = trim($pathWithoutBasePath, "/");
            $ret["/$pathWithoutBasePath"] = $path;
        }
        return $ret;
    }
}