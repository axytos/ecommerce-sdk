<?php

namespace Axytos\ECommerce\UserAgent;

use Axytos\ECommerce\Abstractions\UserAgentInfoProviderInterface;

class UserAgentFactory
{
    /**
     * @var \Axytos\ECommerce\Abstractions\UserAgentInfoProviderInterface
     */
    private $userAgentInfoProvider;

    public function __construct(UserAgentInfoProviderInterface $userAgentInfoProvider)
    {
        $this->userAgentInfoProvider = $userAgentInfoProvider;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        $pluginName = $this->userAgentInfoProvider->getPluginName();
        $pluginVersion = $this->userAgentInfoProvider->getPluginVersion();
        $shopSystemName = $this->userAgentInfoProvider->getShopSystemName();
        $shopSystemVersion = $this->userAgentInfoProvider->getShopSystemVersion();

        return "{$pluginName}/{$pluginVersion} {$shopSystemName}/{$shopSystemVersion}";
    }
}
