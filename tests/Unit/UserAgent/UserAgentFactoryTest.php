<?php

namespace Axytos\ECommerce\Tests\Unit\UserAgent;

use Axytos\ECommerce\Abstractions\UserAgentInfoProviderInterface;
use Axytos\ECommerce\UserAgent\UserAgentFactory;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class UserAgentFactoryTest extends TestCase
{
    /** @var UserAgentInfoProviderInterface|MockObject $userAgentInfoProvider */
    private $userAgentInfoProvider;

    /**
     * @var \Axytos\ECommerce\UserAgent\UserAgentFactory
     */
    private $sut;

    /**
     * @return void
     * @before
     */
    #[Before]
    public function beforeEach()
    {
        $this->userAgentInfoProvider = $this->createMock(UserAgentInfoProviderInterface::class);

        $this->sut = new UserAgentFactory($this->userAgentInfoProvider);
    }

    /**
     * @return void
     */
    public function test_getUserAgent_returns_user_agent_with_plugin_and_shop_system_info()
    {
        $pluginName = "pluginName";
        $pluginVersion = "pluginVersion";
        $shopSystemName = "shopSystemName";
        $shopSystemVersion = "shopSystemVersion";

        $expected = "pluginName/pluginVersion shopSystemName/shopSystemVersion";

        $this->userAgentInfoProvider
            ->method('getPluginName')
            ->willReturn($pluginName);

        $this->userAgentInfoProvider
            ->method('getPluginVersion')
            ->willReturn($pluginVersion);

        $this->userAgentInfoProvider
            ->method('getShopSystemName')
            ->willReturn($shopSystemName);

        $this->userAgentInfoProvider
            ->method('getShopSystemVersion')
            ->willReturn($shopSystemVersion);

        $actual = $this->sut->getUserAgent();

        $this->assertEquals($expected, $actual);
    }
}
