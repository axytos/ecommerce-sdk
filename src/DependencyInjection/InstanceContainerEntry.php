<?php

namespace Axytos\ECommerce\DependencyInjection;

class InstanceContainerEntry implements ContainerEntryInterface
{
    /** @var mixed $instance */
    private $instance;

    /** @param mixed $instance */
    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    /** @return mixed
     * @param \Axytos\ECommerce\DependencyInjection\Container $container */
    public function getInstance($container)
    {
        return $this->instance;
    }
}
