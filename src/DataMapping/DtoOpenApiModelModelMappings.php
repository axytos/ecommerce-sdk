<?php

namespace Axytos\ECommerce\DataMapping;

use Axytos\FinancialServices\OpenAPI\Client\Model\ModelInterface;

class DtoOpenApiModelModelMappings
{
    /**
     * @phpstan-var array<class-string<DtoInterface>,class-string<ModelInterface>>
     *
     * @var mixed[]
     */
    private $mappings;

    /**
     * @phpstan-param array<class-string<DtoInterface>,class-string<ModelInterface>> $mappings
     */
    public function __construct(array $mappings)
    {
        $this->mappings = $mappings;
    }

    /**
     * @param string $dtoClassName
     * @param string $oaModelName
     *
     * @phpstan-param class-string<DtoInterface> $dtoClassName
     * @phpstan-param class-string<ModelInterface> $oaModelName
     *
     * @return bool
     */
    public function hasMapping($dtoClassName, $oaModelName)
    {
        return array_key_exists($dtoClassName, $this->mappings)
            && $this->mappings[$dtoClassName] === $oaModelName;
    }

    /**
     * @param DtoInterface $dto
     *
     * @return string
     *
     * @phpstan-return class-string<ModelInterface>
     */
    public function lookupOpenApiModelName($dto)
    {
        /** @phpstan-var class-string<DtoInterface> */
        $dtoClassName = get_class($dto);

        /** @phpstan-var array<class-string<ModelInterface>> */
        $oaModelNames = $this->mappings;

        return $oaModelNames[$dtoClassName];
    }

    /**
     * @param ModelInterface $model
     *
     * @return string
     *
     * @phpstan-return class-string<DtoInterface>
     */
    public function lookupDtoClassName($model)
    {
        /** @phpstan-var class-string<ModelInterface> */
        $oaModelName = get_class($model);

        /** @phpstan-var array<class-string<DtoInterface>> */
        $dtoClassNames = array_flip($this->mappings);

        return $dtoClassNames[$oaModelName];
    }
}
