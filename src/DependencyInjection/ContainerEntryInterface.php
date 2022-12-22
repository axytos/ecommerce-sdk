<?php

namespace Axytos\ECommerce\DependencyInjection;

interface ContainerEntryInterface
{
    /** @return mixed
     * @param \Axytos\ECommerce\DependencyInjection\Container $container */
    public function getInstance($container);
}
