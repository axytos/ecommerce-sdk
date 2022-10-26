<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Integration\Providers;

use Axytos\ECommerce\Abstractions\UserAgentInfoProviderInterface;

class UserAgentInfoProvider implements UserAgentInfoProviderInterface
{
    public function getPluginName(): string
    {
        return 'axytos-ecommerce-sdk';
    }

    public function getPluginVersion(): string
    {
        return '0.0.0';
    }

    public function getShopSystemName(): string
    {
        return 'integration-tests';
    }

    public function getShopSystemVersion(): string
    {
        return '0.0.0';
    }
}
