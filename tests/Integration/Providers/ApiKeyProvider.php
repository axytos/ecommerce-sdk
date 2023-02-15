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
        $configFilePath = __DIR__ . '/../config/apiKey';

        if (!file_exists($configFilePath)) {
            throw new \Exception('API Key is not configured for integration tests!');
        }

        return strval(file_get_contents($configFilePath));
    }
}
