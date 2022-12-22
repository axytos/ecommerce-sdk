<?php

namespace Axytos\ECommerce\Tests\Integration\Providers;

use Axytos\ECommerce\Abstractions\UserAgentInfoProviderInterface;

class UserAgentInfoProvider implements UserAgentInfoProviderInterface
{
    /**
     * @return string
     */
    public function getPluginName()
    {
        return 'axytos-ecommerce-sdk';
    }

    /**
     * @return string
     */
    public function getPluginVersion()
    {
        return '0.0.0';
    }

    /**
     * @return string
     */
    public function getShopSystemName()
    {
        return 'integration-tests';
    }

    /**
     * @return string
     */
    public function getShopSystemVersion()
    {
        return '0.0.0';
    }
}
