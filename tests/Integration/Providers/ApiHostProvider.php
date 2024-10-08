<?php

namespace Axytos\ECommerce\Tests\Integration\Providers;

use Axytos\ECommerce\Abstractions\ApiHostProviderInterface;

class ApiHostProvider implements ApiHostProviderInterface
{
    /**
     * @return string
     *
     * @phpstan-return self::LIVE|self::SANDBOX
     */
    public function getApiHost()
    {
        return self::SANDBOX;
    }
}
