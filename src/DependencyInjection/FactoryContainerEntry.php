<?php

namespace Axytos\ECommerce\DependencyInjection;

class FactoryContainerEntry implements ContainerEntryInterface
{
    /** @var callable */
    private $factory;

    public function __construct(callable $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Container $container
     */
    public function getInstance($container)
    {
        return call_user_func($this->factory, $container);
    }
}
