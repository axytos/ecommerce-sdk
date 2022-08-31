<?php

declare(strict_types=1);

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

    /** @return mixed */
    public function getInstance(Container $container)
    {
        return $this->instance;
    }
}
