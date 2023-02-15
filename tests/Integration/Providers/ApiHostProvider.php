<?php

namespace Axytos\ECommerce\Tests\Integration\Providers;

use Axytos\ECommerce\Abstractions\ApiHostProviderInterface;

class ApiHostProvider implements ApiHostProviderInterface
{
    /**
     * @return string
     */
    public function getApiHost()
    {
        $configFilePath = __DIR__ . '/../config/apiHost';

        if (!file_exists($configFilePath)) {
            throw new \Exception('API Host is not configured for integration tests!');
        }

        return strval(file_get_contents($configFilePath));
    }
}
