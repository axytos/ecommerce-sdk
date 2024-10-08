<?php

namespace Axytos\ECommerce\DataMapping;

class DtoToDtoMapper
{
    /**
     * @phpstan-template TFromDto of DtoInterface
     * @phpstan-template TFromDtoCollection of DtoCollection<TFromDto>
     * @phpstan-template TToDto of DtoInterface
     * @phpstan-template TToDtoCollection of DtoCollection<TToDto>
     *
     * @phpstan-param TFromDtoCollection $fromDtoCollection
     * @phpstan-param class-string<TToDtoCollection> $toDtoCollectionClassString
     *
     * @phpstan-return TToDtoCollection
     *
     * @param DtoCollection $fromDtoCollection
     * @param string        $toDtoCollectionClassString
     *
     * @return DtoCollection
     */
    public function mapDtoCollection($fromDtoCollection, $toDtoCollectionClassString)
    {
        $reflector = new \ReflectionClass($toDtoCollectionClassString);
        /**
         * @phpstan-var TToDtoCollection
         *
         * @var DtoCollection
         */
        $dtoCollection = $reflector->newInstanceWithoutConstructor();
        /**
         * @phpstan-var class-string<TToDto>
         */
        $elementsClass = $toDtoCollectionClassString::getElementClass();
        /** @phpstan-var TToDto[] */
        $elements = array_map(
            function ($element) use ($elementsClass) {
                return $this->mapDto($element, $elementsClass);
            },
            $fromDtoCollection->getElements()
        );

        /** @phpstan-var TToDtoCollection */
        return $reflector->newInstance(...$elements);
    }

    /**
     * @phpstan-template TFromDto of DtoInterface
     * @phpstan-template TToDto of DtoInterface
     *
     * @phpstan-param TFromDto $fromDto
     * @phpstan-param class-string<TToDto> $toDtoClassString
     *
     * @phpstan-return TToDto
     *
     * @param DtoInterface $fromDto
     * @param string       $toDtoClassString
     *
     * @return DtoInterface
     */
    public function mapDto($fromDto, $toDtoClassString)
    {
        $toDtoReflector = new \ReflectionClass($toDtoClassString);

        /**
         * @phpstan-var TToDto
         *
         * @var DtoInterface
         */
        $toDto = $toDtoReflector->newInstanceWithoutConstructor();
        $toDtoProperties = $toDtoReflector->getProperties(\ReflectionProperty::IS_PUBLIC);
        $fromDtoReflector = new \ReflectionClass($fromDto);
        foreach ($toDtoProperties as $toDtoProperty) {
            $toDtoProeprtyInfo = DtoPropertyInfo::create($toDtoProperty);
            $toDtoPropertyName = $toDtoProperty->getName();

            if (!$fromDtoReflector->hasProperty($toDtoPropertyName)) {
                continue;
            }

            $fromDtoPropertyInfo = DtoPropertyInfo::create($fromDtoReflector->getProperty($toDtoPropertyName));

            $toDtoPropertyTypeName = $toDtoProeprtyInfo->getType();
            $fromDtoPropertyTypeName = $fromDtoPropertyInfo->getType();

            if ($toDtoPropertyTypeName !== $fromDtoPropertyTypeName) {
                continue;
            }
            if (is_subclass_of($toDtoPropertyTypeName, DtoCollection::class)) {
                /** @phpstan-var class-string<DtoCollection<DtoInterface>> $toDtoPropertyTypeName */
                $toDto->{$toDtoPropertyName} = $this->mapDtoCollection($fromDto->{$toDtoPropertyName}, $toDtoPropertyTypeName);
            }
            if (is_subclass_of($toDtoPropertyTypeName, DtoInterface::class)) {
                /** @phpstan-var class-string<DtoInterface> $toDtoPropertyTypeName */
                $toDto->{$toDtoPropertyName} = $this->mapDto($fromDto->{$toDtoPropertyName}, $toDtoPropertyTypeName);
            }
            $toDto->{$toDtoPropertyName} = $fromDto->{$toDtoPropertyName};
        }

        return $toDto;
    }
}
