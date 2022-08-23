<?php declare(strict_types=1);

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
     */
    abstract public static function getElementClass(): string;

    /**
     * @phpstan-var T[]
     */
    private array $values;
    
    /**
     * @phpstan-param T[] $values
     */
    protected function __construct(array $values = [])
    {
        $this->values = $values;
    }

    /**
     * @phpstan-return T[]
     */
    public function getElements(): array
    {
        return $this->values;
    }
    
    /**
     * @phpstan-return Traversable<T>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    /**
     * @phpstan-param int|string $offset
     */
    public function offsetExists($offset): bool
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
     */
    public function offsetSet($offset, $value): void
    {
        $this->values[$offset] = $value;
    }

    /**
     * @phpstan-param int|string $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->values[$offset]);
    }

    public function count(): int
    {
        return count($this->values);
    }
}
