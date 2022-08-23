<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataMapping;

use Axytos\FinancialServicesAPI\Client\Model\ModelInterface;

class DtoOpenApiModelModelMappings
{
    /**
     * @phpstan-var array<class-string<DtoInterface>,class-string<ModelInterface>>
     */
    private array $mappings;

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
     */
    public function hasMapping(string $dtoClassName, string $oaModelName): bool
    {
        return array_key_exists($dtoClassName, $this->mappings)
            && $this->mappings[$dtoClassName] === $oaModelName;
    }

    /** 
     * @phpstan-return class-string<ModelInterface> 
     */
    public function lookupOpenApiModelName(DtoInterface $dto): string
    {
        /** @phpstan-var class-string<DtoInterface> */
        $dtoClassName = get_class($dto);

        /** @phpstan-var array<class-string<ModelInterface>> */
        $oaModelNames = $this->mappings;

        return $oaModelNames[$dtoClassName];
    }

    /**
     * @phpstan-return class-string<DtoInterface>
     */
    public function lookupDtoClassName(ModelInterface $model): string
    {
        /** @phpstan-var class-string<ModelInterface> */
        $oaModelName = get_class($model);

        /** @phpstan-var array<class-string<DtoInterface>> */
        $dtoClassNames = array_flip($this->mappings);

        return $dtoClassNames[$oaModelName];
    }
}