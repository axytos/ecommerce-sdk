<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Abstractions;

interface ApiHostProviderInterface
{
    public function getApiHost(): string;
}
