<?php

namespace Axytos\ECommerce\DependencyInjection;

class Container
{
    /** @var ContainerEntryInterface[] */
    private $containerEntries;

    /**
     * @param ContainerEntryInterface[] $containerEntries
     */
    public function __construct(array $containerEntries)
    {
        $this->containerEntries = $containerEntries;
    }

    /**
     * @return string[]
     *
     * @phpstan-return class-string[]
     */
    public function keys()
    {
        /** @phpstan-var class-string[] */
        return array_keys($this->containerEntries);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->containerEntries);
    }

    /**
     * @param string $key
     *
     * @return object
     *
     * @template T
     *
     * @phpstan-param class-string<T> $key
     *
     * @phpstan-return T
     */
    public function get($key)
    {
        if ($this->has($key)) {
            $containerEntry = $this->containerEntries[$key];

            return $containerEntry->getInstance($this);
        }

        throw new NotFoundException($key);
    }
}
