<?php

namespace Axytos\ECommerce\DataMapping;

use DateTimeInterface;
use ReflectionClass;

class DtoArrayMapper
{
    /**
     * @phpstan-param DtoInterface $dto
     * @phpstan-return array<mixed>
     * @param \Axytos\ECommerce\DataMapping\DtoInterface $dto
     * @return array<mixed>
     */
    public function toArray($dto)
    {
        return array_map([$this,'toArrayValue'], get_object_vars($dto));
    }

    /**
     * @phpstan-param DateTimeInterface|DtoInterface|DtoCollection<DtoInterface>|array<mixed> $value
     * @phpstan-return mixed
     * @param DateTimeInterface|DtoInterface|DtoCollection<DtoInterface>|array<mixed> $value
     * @return string|array<mixed>
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
     * @phpstan-param array<mixed> $array
     * @phpstan-param class-string<T> $dtoClassName
     * @phpstan-return T
     * @param array<mixed> $array
     * @param string $dtoClassName
     * @return \Axytos\ECommerce\DataMapping\DtoInterface
     */
    public function fromArray($array, $dtoClassName)
    {
        $reflector = new ReflectionClass($dtoClassName);

        /** @phpstan-var T */
        $dto = $reflector->newInstanceWithoutConstructor();

        foreach ($reflector->getProperties() as $property) {
            $name = $property->getName();

            $dtoPropertyInfo = DtoPropertyInfo::create($property);
            if (array_key_exists($name, $array)) {
                $value = $this->fromArrayValue($array[$name], $dtoPropertyInfo->getType());
                $property->setValue($dto, $value);
            }
        }

        return $dto;
    }

    /**
     * @phpstan-param mixed $value
     * @phpstan-param class-string<DateTimeInterface>|class-string<DtoInterface>|class-string<DtoCollection<DtoInterface>>|string $type
     * @param mixed $value
     * @param string $type
     * @return mixed
     */
    private function fromArrayValue($value, $type)
    {
        if (is_string($value) && is_a($type, DateTimeInterface::class, true)) {
            $serializer = new DateTimeSerializer();
            return $serializer->deserialize($value);
        }

        if (is_array($value) && is_a($type, DtoInterface::class, true)) {
            /** @phpstan-var class-string<DtoInterface> */
            $dtoClassName = $type;
            return $this->fromArray($value, $dtoClassName);
        }

        if (is_array($value) && is_a($type, DtoCollection::class, true)) {
            /** @phpstan-var class-string<DtoCollection<DtoInterface>> */
            $dtoCollectionClassName = $type;
            return $this->createDtoCollection($value, $dtoCollectionClassName);
        }

        return $value;
    }

    /**
     * @phpstan-template T of DtoCollection
     * @phpstan-param mixed[] $values
     * @phpstan-param class-string<T> $dtoCollectionClassName
     * @phpstan-return T
     * @param mixed[] $values
     * @param string $dtoCollectionClassName
     * @return \Axytos\ECommerce\DataMapping\DtoCollection
     */
    private function createDtoCollection($values, $dtoCollectionClassName)
    {
        $dtoCollectionClassName = (string) $dtoCollectionClassName;

        /** @phpstan-var class-string<DtoInterface> */
        $dtoClassName = $dtoCollectionClassName::getElementClass();

        $elements = array_map(function ($value) use ($dtoClassName) {
            return $this->fromArray((array)$value, $dtoClassName);
        }, $values);

        return new $dtoCollectionClassName(...$elements);
    }
}
