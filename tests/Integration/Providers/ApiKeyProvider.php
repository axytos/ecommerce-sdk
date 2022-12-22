<?php

namespace Axytos\ECommerce\Tests\Integration\Providers;

use Axytos\ECommerce\Abstractions\ApiKeyProviderInterface;

class ApiKeyProvider implements ApiKeyProviderInterface
{
    /**
     * @return string
     */
    public function getApiKey()
    {
        return strval(file_get_contents(__DIR__ . '/../config/apiKey'));
    }
}
