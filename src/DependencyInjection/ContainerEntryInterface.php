<?php

namespace Axytos\ECommerce\DependencyInjection;

interface ContainerEntryInterface
{
    /**
     * @param Container $container
     *
     * @return mixed
     */
    public function getInstance($container);
}
