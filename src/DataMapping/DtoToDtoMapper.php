<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataMapping;

use Error;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;

class DtoToDtoMapper
{
    /**
     * @phpstan-template T of DtoCollection
     * @phpstan-param DtoCollection $fromDtoCollection
     * @phpstan-param class-string<T> $toDtoCollectionClassString
     * @phpstan-return T
     */
    public function mapDtoCollection($fromDtoCollection, $toDtoCollectionClassString)
    {
        $reflector = new ReflectionClass($toDtoCollectionClassString);
        $dtoCollection = $reflector->newInstanceWithoutConstructor();
        $elementsClass = $dtoCollection->getElementClass();
        $elements = array_map(
            function ($element) use ($elementsClass) {
                return $this->mapDto($element, $elementsClass);
            },
            $fromDtoCollection->getElements()
        );
        $dtoCollection = $reflector->newInstance(...$elements);
        return $dtoCollection;
    }

    /**
     * @phpstan-template T of DtoInterface
     * @phpstan-param DtoInterface $fromDto
     * @phpstan-param class-string<T> $toDtoClassString
     * @phpstan-return T
     */
    public function mapDto($fromDto, $toDtoClassString)
    {
        $toDtoReflector = new ReflectionClass($toDtoClassString);
        $toDto = $toDtoReflector->newInstanceWithoutConstructor();
        $toDtoProperties = $toDtoReflector->getProperties(ReflectionProperty::IS_PUBLIC);
        $fromDtoReflector = new ReflectionClass($fromDto);
        foreach ($toDtoProperties as $toDtoProperty) {
            $toDtoPropertyName = $toDtoProperty->getName();
            $toDtoPropertyType = $toDtoProperty->getType();
            if(!$fromDtoReflector->hasProperty($toDtoPropertyName)) {
                continue;
            }
            $fromDtoPropertyType = $fromDtoReflector->getProperty($toDtoPropertyName)->getType();
            if(!$toDtoPropertyType instanceof ReflectionNamedType || !$fromDtoPropertyType instanceof ReflectionNamedType) {
                continue;
            }
            $toDtoPropertyTypeName = $toDtoPropertyType->getName();
            $fromDtoPropertyTypeName = $fromDtoPropertyType->getName();
            if($toDtoPropertyTypeName !== $fromDtoPropertyTypeName) {
                continue;
            }
            if (is_subclass_of($toDtoPropertyTypeName, DtoCollection::class)) {
                $toDto->{$toDtoPropertyName} =  $this->mapDtoCollection($fromDto->{$toDtoPropertyName}, $toDtoPropertyTypeName);
            }
            if (is_subclass_of($toDtoPropertyTypeName, DtoInterface::class)) {
                $toDto->{$toDtoPropertyName} = $this->mapDto($fromDto->{$toDtoPropertyName}, $toDtoPropertyTypeName);
            }
            $toDto->{$toDtoPropertyName} = $fromDto->{$toDtoPropertyName};
        }
        return $toDto;
    }
}
