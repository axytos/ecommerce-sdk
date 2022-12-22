<?php

namespace Axytos\ECommerce\DataMapping;

use Axytos\FinancialServices\OpenAPI\Client\Model\ModelInterface;

/**
 * @phpstan-template T of ModelInterface
 */
class OpenApiModelAttributeInfo
{
    /**
     * @return OpenApiModelAttributeInfo[]
     * @param \Axytos\FinancialServices\OpenAPI\Client\Model\ModelInterface $oaModel
     */
    public static function getAttributeInfos($oaModel)
    {
        /** @phpstan-var class-string<ModelInterface> */
        $oaModelName = get_class($oaModel);

        /** @phpstan-var array<string,string> */
        $names = call_user_func([$oaModelName, "attributeMap"]);
        /** @phpstan-var array<string,string> */
        $types = call_user_func([$oaModelName, "openAPITypes"]);
        /** @phpstan-var array<string,string> */
        $formats = call_user_func([$oaModelName, "openAPIFormats"]);
        /** @phpstan-var array<string,string> */
        $setters = call_user_func([$oaModelName, "setters"]);
        /** @phpstan-var array<string,string> */
        $getters = call_user_func([$oaModelName, "getters"]);

        /** @var string[] */
        $keys = array_keys($names);

        return array_map(function ($key) use ($oaModelName, $names, $types, $formats, $getters, $setters) {
            return new OpenApiModelAttributeInfo(
                $oaModelName,
                $names[$key],
                $types[$key],
                $formats[$key],
                $getters[$key],
                $setters[$key]
            );
        }, $keys);
    }

    /** @phpstan-var class-string<T>
     * @var string */
    private $modelName;

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $typeName;
    /**
     * @var string|null
     */
    private $format;
    /**
     * @var string
     */
    private $getterName;
    /**
     * @var string
     */
    private $setterName;

    /**
     * @phpstan-param class-string<T> $modelName
     * @phpstan-param string $name
     * @phpstan-param string $typeName
     * @phpstan-param ?string $format
     * @phpstan-param string $getterName
     * @phpstan-param string $setterName
     * @param string|null $format
     * @param string $modelName
     * @param string $name
     * @param string $typeName
     * @param string $getterName
     * @param string $setterName
     */
    private function __construct(
        $modelName,
        $name,
        $typeName,
        $format,
        $getterName,
        $setterName
    ) {
        $modelName = (string) $modelName;
        $name = (string) $name;
        $typeName = (string) $typeName;
        $getterName = (string) $getterName;
        $setterName = (string) $setterName;
        $this->modelName = $modelName;
        $this->name = $name;
        $this->typeName = $typeName;
        $this->format = $format;
        $this->getterName = $getterName;
        $this->setterName = $setterName;
    }

    /**
     * @phpstan-return class-string<T>
     * @return string
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * @return string|null
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param \Axytos\FinancialServices\OpenAPI\Client\Model\ModelInterface $oaModel
     * @return mixed
     */
    public function getValue($oaModel)
    {
        /** @var callable */
        $getter = [$oaModel, $this->getterName];
        return call_user_func($getter);
    }

    /**
     * @param \Axytos\FinancialServices\OpenAPI\Client\Model\ModelInterface $oaModel
     * @param mixed $value
     * @return void
     */
    public function setValue($oaModel, $value)
    {
        /** @var callable */
        $setter = [$oaModel, $this->setterName];
        call_user_func($setter, $value);
    }
}
