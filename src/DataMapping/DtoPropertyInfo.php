<?php

namespace Axytos\ECommerce\DataMapping;

class DtoPropertyInfo
{
    /**
     * @param \ReflectionProperty $property
     *
     * @return DtoPropertyInfo
     */
    public static function create($property)
    {
        return new DtoPropertyInfo($property);
    }

    /**
     * @var \ReflectionProperty
     */
    private $property;

    private function __construct(\ReflectionProperty $property)
    {
        $this->property = $property;
    }

    /**
     * @param string $typeName
     *
     * @return bool
     */
    public function hasType($typeName)
    {
        $type = $this->getTypeName();

        return is_a($type, $typeName, true);
    }

    /**
     * @return bool
     */
    public function hasDtoCollectionType()
    {
        return $this->hasType(DtoCollection::class);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getTypeName();
    }

    /**
     * @param DtoInterface $dto
     *
     * @return mixed
     */
    public function getValue($dto)
    {
        return $this->property->getValue($dto);
    }

    /**
     * @param DtoInterface $dto
     * @param mixed        $value
     *
     * @return void
     */
    public function setValue($dto, $value)
    {
        $this->property->setValue($dto, $value);
    }

    /**
     * @return string
     */
    private function getTypeName()
    {
        // php 7
        /** @var object|null */
        $propertyType = (object) (method_exists($this->property, 'getType') ? $this->property->getType() : null);
        if (!is_null($propertyType) && method_exists($this->property, 'getType')) {
            if (method_exists($propertyType, 'getName')) {
                return $propertyType->getName();
            }
        }

        // php 5
        $docComment = (string) $this->property->getDocComment();

        $matches = [];
        preg_match('/@var\s+(?P<type>.+)/', $docComment, $matches);

        if (isset($matches['type'])) {
            $type = $matches['type'];
            $type = explode('|', $type)[0];
            $type = explode('&', $type)[0];
            $type = trim($type);

            if (self::isPrimitiveTypeName($type)) {
                return $type;
            }

            if (self::isArrayTypeName($type)) {
                return 'array';
            }

            if (class_exists($type) || interface_exists($type)) {
                return $type;
            }

            $candidateType = $this->property->getDeclaringClass()->getNamespaceName() . '\\' . $type;
            if (class_exists($candidateType) || interface_exists($candidateType)) {
                return $candidateType;
            }

            return $type;
        }

        return 'mixed';
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private static function isPrimitiveTypeName($type)
    {
        $primitiveTypes = ['int', 'float', 'double', 'string', 'bool', 'array', 'object', 'mixed', 'null'];

        return in_array(strtolower($type), $primitiveTypes, true);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private static function isArrayTypeName($type)
    {
        return 'array' === strtolower($type)
                || 'array<' === substr(strtolower($type), 0, 6)
                || '[]' === substr(strtolower($type), -2);
    }
}
