<?php declare(strict_types=1);

namespace Axytos\ECommerce\UserAgent;

use Axytos\ECommerce\Abstractions\UserAgentInfoProviderInterface;

class UserAgentFactory
{
    private UserAgentInfoProviderInterface $userAgentInfoProvider;

    public function __construct(UserAgentInfoProviderInterface $userAgentInfoProvider)
    {
        $this->userAgentInfoProvider = $userAgentInfoProvider;
    }

    public function getUserAgent(): string
    {
        $pluginName = $this->userAgentInfoProvider->getPluginName();
        $pluginVersion = $this->userAgentInfoProvider->getPluginVersion();
        $shopSystemName = $this->userAgentInfoProvider->getShopSystemName();
        $shopSystemVersion = $this->userAgentInfoProvider->getShopSystemVersion();

        return "{$pluginName}/{$pluginVersion} {$shopSystemName}/{$shopSystemVersion}";
    }
}