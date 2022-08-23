<?php declare(strict_types=1);

namespace Axytos\ECommerce\DependencyInjection;

class ContainerBuilder
{
    private array $containerEntries = [];

    /** @param class-string|string $className */
    public function registerClass(string $className, string ...$aliasNames): void
    {
        if (!class_exists($className)) {
			return;
		}
        
        foreach($aliasNames as $aliasName)
        {
            $this->containerEntries[$aliasName] = new ClassContainerEntry($className);
        }
    }

    public function registerClassMap(array $classMap): void
    {
        /** @var class-string|string $className */
        foreach($classMap as $className => $aliasNames)
        {
            $this->registerClass($className, ...$aliasNames);
        }
    }

    /** @param InstanceContainerEntry $instance */
    public function registerInstance($instance, string ...$aliasNames): void
    {
        foreach($aliasNames as $aliasName)
        {
            $this->containerEntries[$aliasName] = new InstanceContainerEntry($instance);
        }
    }

    public function registerInstanceMap(array $instanceMap): void
    {
        foreach($instanceMap as $aliasName => $instance)
        {
            $this->registerInstance($instance, $aliasName);
        }
    }

    public function registerFactory(string $aliasName, callable $factory): void
    {
        $this->containerEntries[$aliasName] = new FactoryContainerEntry($factory);
    }

    public function build(): Container
    {
        return new Container($this->containerEntries);
    }
}
