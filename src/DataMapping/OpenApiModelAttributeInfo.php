<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataMapping;

use Axytos\FinancialServicesAPI\Client\Model\ModelInterface;

/**
 * @phpstan-template T of ModelInterface
 */
class OpenApiModelAttributeInfo
{
    /**
     * @return OpenApiModelAttributeInfo[]
     */
    public static function getAttributeInfos(ModelInterface $oaModel): array
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

        return array_map(function($key) use ($oaModelName, $names, $types, $formats, $getters, $setters){
            return new OpenApiModelAttributeInfo(
                $oaModelName,
                $names[$key],
                $types[$key],
                $formats[$key],
                $getters[$key],
                $setters[$key]);
        }, $keys);
    }

    /** @phpstan-var class-string<T> */
    private string $modelName;

    private string $name;
    private string $typeName;
    private ?string $format;
    private string $getterName;
    private string $setterName;

    /**
     * @phpstan-param class-string<T> $modelName
     * @phpstan-param string $name
     * @phpstan-param string $typeName
     * @phpstan-param ?string $format
     * @phpstan-param string $getterName
     * @phpstan-param string $setterName
     */
    private function __construct(
        string $modelName,
        string $name,
        string $typeName,
        ?string $format,
        string $getterName,
        string $setterName)
    {
        $this->modelName = $modelName;
        $this->name = $name;
        $this->typeName = $typeName;
        $this->format = $format;
        $this->getterName = $getterName;
        $this->setterName = $setterName;
    }

    /**
     * @phpstan-return class-string<T>
     */
    public function getModelName(): string
    {
        return $this->modelName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTypeName(): string
    {
        return $this->typeName;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    /**
     * @param ModelInterface $oaModel
     * @return mixed
     */
    public function getValue(ModelInterface $oaModel)
    {
        /** @var callable */
        $getter = [$oaModel, $this->getterName];
        return call_user_func($getter);
    }

    /**
     * @param ModelInterface $oaModel
     * @param mixed $value
     * @return void
     */
    public function setValue(ModelInterface $oaModel, $value): void
    {
        /** @var callable */
        $setter = [$oaModel, $this->setterName];
        call_user_func($setter, $value);
    }
}
