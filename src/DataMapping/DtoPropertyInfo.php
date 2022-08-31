<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataMapping;

use ReflectionNamedType;
use ReflectionProperty;

class DtoPropertyInfo
{
    public static function create(ReflectionProperty $property): DtoPropertyInfo
    {
        return new DtoPropertyInfo($property);
    }

    private ReflectionProperty $property;

    private function __construct(ReflectionProperty $property)
    {
        $this->property = $property;
    }

    public function hasType(string $typeName): bool
    {
        $type = $this->property->getType();

        return $type instanceof ReflectionNamedType
            && is_a($type->getName(), $typeName, true);
    }

    public function hasDtoCollectionType(): bool
    {
        return $this->hasType(DtoCollection::class);
    }

    public function getType(): string
    {
        $type = $this->property->getType();

        if ($type instanceof ReflectionNamedType) {
            return $type->getName();
        }

        return 'mixed';
    }

    /**
     * @param DtoInterface $dto
     * @return mixed
     */
    public function getValue(DtoInterface $dto)
    {
        return $this->property->getValue($dto);
    }

    /**
     * @param DtoInterface $dto
     * @param mixed $value
     * @return void
     */
    public function setValue(DtoInterface $dto, $value): void
    {
        $this->property->setValue($dto, $value);
    }
}
