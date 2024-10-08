<?php

namespace Axytos\ECommerce\DependencyInjection;

class InstanceContainerEntry implements ContainerEntryInterface
{
    /**
     * @var mixed
     */
    private $instance;

    /**
     * @param mixed $instance
     *
     * @return void
     */
    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    /**
     * @param Container $container
     *
     * @return mixed
     */
    public function getInstance($container)
    {
        return $this->instance;
    }
}
