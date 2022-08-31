<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DependencyInjection;

interface ContainerEntryInterface
{
    /** @return mixed */
    public function getInstance(Container $container);
}
