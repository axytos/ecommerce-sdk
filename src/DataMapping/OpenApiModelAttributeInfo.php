<?php

namespace Axytos\ECommerce\DataMapping;

/**
 * @phpstan-template TModelInterface of \Axytos\FinancialServices\OpenAPI\Client\Model\ModelInterface
 */
class OpenApiModelAttributeInfo
{
    /**
     * @phpstan-template TModel of \Axytos\FinancialServices\OpenAPI\Client\Model\ModelInterface
     *
     * @param \Axytos\FinancialServices\OpenAPI\Client\Model\ModelInterface $oaModel
     *
     * @phpstan-param TModel $oaModel
     *
     * @return OpenApiModelAttributeInfo[]
     *
     * @phpstan-return OpenApiModelAttributeInfo<TModel>[]
     */
    public static function getAttributeInfos($oaModel)
    {
        /** @phpstan-var class-string<TModel> */
        $oaModelName = get_class($oaModel);

        /** @phpstan-var array<string,string> */
        $names = call_user_func([$oaModelName, 'attributeMap']);
        /** @phpstan-var array<string,string> */
        $types = call_user_func([$oaModelName, 'openAPITypes']);
        /** @phpstan-var array<string,string> */
        $formats = call_user_func([$oaModelName, 'openAPIFormats']);
        /** @phpstan-var array<string,string> */
        $setters = call_user_func([$oaModelName, 'setters']);
        /** @phpstan-var array<string,string> */
        $getters = call_user_func([$oaModelName, 'getters']);

        /** @var string[] */
        $keys = array_keys($names);

        return array_map(function ($key) use ($oaModelName, $names, $types, $formats, $getters, $setters) {
            /** @phpstan-var OpenApiModelAttributeInfo<TModel> */
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

    /** @phpstan-var class-string<TModelInterface>
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
     * @param string|null $format
     * @param string      $modelName
     * @param string      $name
     * @param string      $typeName
     * @param string      $getterName
     * @param string      $setterName
     *
     * @phpstan-param class-string<TModelInterface> $modelName
     * @phpstan-param string $name
     * @phpstan-param string $typeName
     * @phpstan-param ?string $format
     * @phpstan-param string $getterName
     * @phpstan-param string $setterName
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
     * @return string
     *
     * @phpstan-return class-string<TModelInterface>
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
     *
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
     * @param mixed                                                         $value
     *
     * @return void
     */
    public function setValue($oaModel, $value)
    {
        /** @var callable */
        $setter = [$oaModel, $this->setterName];
        call_user_func($setter, $value);
    }
}
