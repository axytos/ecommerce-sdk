<?php

namespace Axytos\ECommerce\DependencyInjection;

class ContainerBuilder
{
    /**
     * @var ContainerEntryInterface[]
     */
    private $containerEntries = [];

    /**
     * @param mixed $instance
     * @param string ...$aliasNames
     * @return void
     */
    public function registerInstance($instance, ...$aliasNames)
    {
        foreach ($aliasNames as $aliasName) {
            $this->containerEntries[$aliasName] = new InstanceContainerEntry($instance);
        }
    }

    /**
     * @param mixed[] $instanceMap
     * @return void
     */
    public function registerInstanceMap($instanceMap)
    {
        foreach ($instanceMap as $aliasName => $instance) {
            $this->registerInstance($instance, $aliasName);
        }
    }

    /**
     * @param string $aliasName
     * @param callable $factory
     * @return void
     */
    public function registerFactory($aliasName, $factory)
    {
        $this->containerEntries[$aliasName] = new FactoryContainerEntry($factory);
    }

    /**
     * @return \Axytos\ECommerce\DependencyInjection\Container
     */
    public function build()
    {
        return new Container($this->containerEntries);
    }
}
