<?php

namespace Axytos\ECommerce\DataMapping;

use Axytos\FinancialServices\OpenAPI\Client\Model\ModelInterface;

class DtoOpenApiModelModelMappings
{
    /**
     * @phpstan-var array<class-string<DtoInterface>,class-string<ModelInterface>>
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
     * @phpstan-param class-string<DtoInterface> $dtoClassName
     * @phpstan-param class-string<ModelInterface> $oaModelName
     * @param string $dtoClassName
     * @param string $oaModelName
     * @return bool
     */
    public function hasMapping($dtoClassName, $oaModelName)
    {
        return array_key_exists($dtoClassName, $this->mappings)
            && $this->mappings[$dtoClassName] === $oaModelName;
    }

    /**
     * @phpstan-return class-string<ModelInterface>
     * @param \Axytos\ECommerce\DataMapping\DtoInterface $dto
     * @return string
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
     * @phpstan-return class-string<DtoInterface>
     * @param \Axytos\FinancialServices\OpenAPI\Client\Model\ModelInterface $model
     * @return string
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
