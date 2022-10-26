<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Integration\Providers;

use Axytos\ECommerce\Abstractions\ApiKeyProviderInterface;

class ApiKeyProvider implements ApiKeyProviderInterface
{
    public function getApiKey(): string
    {
        return strval(file_get_contents(__DIR__ . '/../config/apiKey'));
    }
}
