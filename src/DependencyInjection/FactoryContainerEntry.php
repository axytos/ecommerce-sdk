<?php

namespace Axytos\ECommerce\DependencyInjection;

class FactoryContainerEntry implements ContainerEntryInterface
{
    /** @var callable $factory */
    private $factory;

    public function __construct(callable $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param \Axytos\ECommerce\DependencyInjection\Container $container
     */
    public function getInstance($container)
    {
        return call_user_func($this->factory, $container);
    }
}
