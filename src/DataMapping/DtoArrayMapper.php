<?php

namespace Axytos\ECommerce\DataMapping;

use DateTimeInterface;

class DtoArrayMapper
{
    /**
     * @param DtoInterface $dto
     *
     * @phpstan-param DtoInterface $dto
     *
     * @return array<mixed>
     *
     * @phpstan-return array<mixed>
     */
    public function toArray($dto)
    {
        return array_map([$this, 'toArrayValue'], get_object_vars($dto));
    }

    /**
     * @param \DateTimeInterface|DtoInterface|DtoCollection<DtoInterface>|array<mixed> $value
     *
     * @phpstan-param DateTimeInterface|DtoInterface|DtoCollection<DtoInterface>|array<mixed> $value
     *
     * @return string|array<mixed>
     *
     * @phpstan-return mixed
     */
    private function toArrayValue($value)
    {
        if ($value instanceof \DateTimeInterface) {
            $serializer = new DateTimeSerializer();

            return $serializer->serialize($value);
        }

        if ($value instanceof DtoInterface) {
            return $this->toArrayValue(get_object_vars($value));
        }

        if ($value instanceof DtoCollection) {
            return array_map([$this, 'toArrayValue'], $value->getElements());
        }

        if (is_array($value)) {
            return array_map([$this, 'toArrayValue'], $value);
        }

        return $value;
    }

    /**
     * @phpstan-template T of DtoInterface
     *
     * @param array<mixed> $array
     * @param string       $dtoClassName
     *
     * @phpstan-param array<mixed> $array
     * @phpstan-param class-string<T> $dtoClassName
     *
     * @return DtoInterface
     *
     * @phpstan-return T
     */
    public function fromArray($array, $dtoClassName)
    {
        $reflector = new \ReflectionClass($dtoClassName);

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
     * @param mixed  $value
     * @param string $type
     *
     * @phpstan-param mixed $value
     * @phpstan-param class-string<DateTimeInterface>|class-string<DtoInterface>|class-string<DtoCollection<DtoInterface>>|string $type
     *
     * @return mixed
     */
    private function fromArrayValue($value, $type)
    {
        if (is_string($value) && is_a($type, \DateTimeInterface::class, true)) {
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
     *
     * @param mixed[] $values
     * @param string  $dtoCollectionClassName
     *
     * @phpstan-param mixed[] $values
     * @phpstan-param class-string<T> $dtoCollectionClassName
     *
     * @return DtoCollection
     *
     * @phpstan-return T
     */
    private function createDtoCollection($values, $dtoCollectionClassName)
    {
        $dtoCollectionClassName = (string) $dtoCollectionClassName;

        /** @phpstan-var class-string<DtoInterface> */
        $dtoClassName = $dtoCollectionClassName::getElementClass();

        $elements = array_map(function ($value) use ($dtoClassName) {
            return $this->fromArray((array) $value, $dtoClassName);
        }, $values);

        return new $dtoCollectionClassName(...$elements);
    }
}
