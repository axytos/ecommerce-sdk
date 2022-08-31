<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DependencyInjection;

class FactoryContainerEntry implements ContainerEntryInterface
{
    /** @var callable $factory */
    private $factory;

    public function __construct(callable $factory)
    {
        $this->factory = $factory;
    }

    public function getInstance(Container $container)
    {
        return call_user_func($this->factory, $container);
    }
}
