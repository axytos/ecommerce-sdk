<?php

namespace Axytos\ECommerce\DataMapping;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @phpstan-template T of DtoInterface
 */
abstract class DtoCollection implements IteratorAggregate, ArrayAccess, Countable
{
    /**
     * @phpstan-return class-string<T>
     * @return string
     */
    abstract public static function getElementClass();

    /**
     * @phpstan-var T[]
     * @var mixed[]
     */
    private $values;

    /**
     * @phpstan-param T[] $values
     */
    protected function __construct(array $values = [])
    {
        $this->values = $values;
    }

    /**
     * @phpstan-return T[]
     * @return mixed[]
     */
    public function getElements()
    {
        return $this->values;
    }

    /**
     * @phpstan-return Traversable<T>
     * @return \Traversable
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->values);
    }

    /**
     * @phpstan-param int|string $offset
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->values);
    }

    /**
     * @phpstan-param int|string $offset
     * @phpstan-return T
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->values[$offset];
    }

    /**
     * @phpstan-param int|string $offset
     * @phpstan-param T $value
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->values[$offset] = $value;
    }

    /**
     * @phpstan-param int|string $offset
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->values[$offset]);
    }

    /**
     * @return int
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->values);
    }
}
