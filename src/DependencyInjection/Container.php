<?php declare(strict_types=1);

namespace Axytos\ECommerce\DependencyInjection;

class Container
{
    /** @var ContainerEntryInterface[] $containerEntries */
    private array $containerEntries;

    public function __construct(array $containerEntries)
    {
        $this->containerEntries = $containerEntries;
    }

    /** @return int[]|string[] */
    public function keys()
    {
        return array_keys($this->containerEntries);
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->containerEntries);
    }

    /**
     * @template T
     * @param class-string<T> $key
     * @return T
     */
    public function get(string $key)
    {
        if ($this->has($key))
        {
            $containerEntry = $this->containerEntries[$key];
	        /** @phpstan-ignore-next-line */
            return $containerEntry->getInstance($this);
        }

        throw new NotFoundException($key);
    }
}
