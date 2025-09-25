<?php

namespace Axytos\ECommerce\DataMapping;

use ArrayAccess;
use IteratorAggregate;
use Traversable;

/**
 * @phpstan-template TDtoInterface of DtoInterface
 *
 * @phpstan-implements ArrayAccess<int|string,TDtoInterface>
 * @phpstan-implements IteratorAggregate<int|string,TDtoInterface>
 */
abstract class DtoCollection implements \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * @return string
     *
     * @phpstan-return class-string<TDtoInterface>
     */
    abstract public static function getElementClass();

    /**
     * @phpstan-var TDtoInterface[]
     *
     * @var mixed[]
     */
    private $values;

    /**
     * @phpstan-param TDtoInterface[] $values
     */
    protected function __construct(array $values = [])
    {
        $this->values = $values;
    }

    /**
     * @return mixed[]
     *
     * @phpstan-return TDtoInterface[]
     */
    public function getElements()
    {
        return $this->values;
    }

    /**
     * @return \Traversable
     *
     * @phpstan-return Traversable<TDtoInterface>
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new \ArrayIterator($this->values);
    }

    /**
     * @phpstan-param int|string $offset
     *
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->values);
    }

    /**
     * @phpstan-param int|string $offset
     *
     * @phpstan-return TDtoInterface
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->values[$offset];
    }

    /**
     * @phpstan-param int|string|null $offset
     * @phpstan-param TDtoInterface $value
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->values[$offset] = $value;
    }

    /**
     * @phpstan-param int|string $offset
     *
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
