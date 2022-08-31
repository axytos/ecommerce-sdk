<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Abstractions;

interface ApiKeyProviderInterface
{
    public function getApiKey(): string;
}
