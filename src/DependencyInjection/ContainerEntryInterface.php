<?php declare(strict_types=1);

namespace Axytos\ECommerce\DependencyInjection;

interface ContainerEntryInterface
{
    /** @return mixed */
    function getInstance(Container $container);
}