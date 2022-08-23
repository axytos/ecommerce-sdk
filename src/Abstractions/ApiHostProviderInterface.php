<?php declare(strict_types=1);

namespace Axytos\ECommerce\Abstractions;

interface ApiHostProviderInterface
{
    function getApiHost(): string;
}