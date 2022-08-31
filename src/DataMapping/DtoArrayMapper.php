<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataMapping;

use DateTimeInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionType;

class DtoArrayMapper
{
    /**
     * @phpstan-param DtoInterface $dto
     * @phpstan-return array
     */
    public function toArray(DtoInterface $dto): array
    {
        return array_map([$this,'toArrayValue'], get_object_vars($dto));
    }

    /**
     * @phpstan-param mixed $value
     * @phpstan-return mixed
     */
    private function toArrayValue($value)
    {
        if ($value instanceof DateTimeInterface) {
            $serializer = new DateTimeSerializer();
            return $serializer->serialize($value);
        }

        if ($value instanceof DtoInterface) {
            return $this->toArrayValue(get_object_vars($value));
        }

        if ($value instanceof DtoCollection) {
            return array_map([$this,'toArrayValue'], $value->getElements());
        }

        if (is_array($value)) {
            return array_map([$this,'toArrayValue'], $value);
        }

        return $value;
    }

    /**
     * @phpstan-template T of DtoInterface
     * @phpstan-param array $array
     * @phpstan-param class-string<T> $dtoClassName
     * @phpstan-return T
     */
    public function fromArray(array $array, string $dtoClassName): DtoInterface
    {
        $reflector = new ReflectionClass($dtoClassName);

        /** @phpstan-var T */
        $dto = $reflector->newInstanceWithoutConstructor();

        foreach ($reflector->getProperties() as $property) {
            $name = $property->getName();

            if (array_key_exists($name, $array)) {
                $value = $this->fromArrayValue($array[$name], $property->getType());
                $property->setValue($dto, $value);
            }
        }

        return $dto;
    }

    /**
     * @phpstan-param mixed $value
     * @phpstan-param ?ReflectionType $type
     * @phpstan-return mixed
     */
    private function fromArrayValue($value, ?ReflectionType $type)
    {
        if ($type instanceof ReflectionNamedType) {
            if (is_string($value) && is_a($type->getName(), DateTimeInterface::class, true)) {
                $serializer = new DateTimeSerializer();
                return $serializer->deserialize($value);
            }

            if (is_array($value) && is_a($type->getName(), DtoInterface::class, true)) {
                /** @phpstan-var class-string<DtoInterface> */
                $dtoClassName = $type->getName();
                return $this->fromArray($value, $dtoClassName);
            }

            if (is_array($value) && is_a($type->getName(), DtoCollection::class, true)) {
                /** @phpstan-var class-string<DtoCollection> */
                $dtoCollectionClassName = $type->getName();
                return $this->createDtoCollection($value, $dtoCollectionClassName);
            }
        }

        return $value;
    }

    /**
     * @phpstan-template T of DtoCollection
     * @phpstan-param array $values
     * @phpstan-param class-string<T> $dtoCollectionClassName
     * @phpstan-return T
     */
    private function createDtoCollection(array $values, string $dtoCollectionClassName): DtoCollection
    {
        /** @phpstan-var class-string<DtoInterface> */
        $dtoClassName = $dtoCollectionClassName::getElementClass();

        $elements = array_map(function ($value) use ($dtoClassName) {
            return $this->fromArray($value, $dtoClassName);
        }, $values);

        return new $dtoCollectionClassName(...$elements);
    }
}
