<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Integration;

use Axytos\ECommerce\Abstractions\ApiKeyProviderInterface;
use Axytos\ECommerce\AxytosECommerceClient;
use Axytos\ECommerce\Clients\Checkout\CheckoutClientInterface;
use Axytos\ECommerce\Clients\Checkout\CreditCheckAgreementLoadFailedException;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\Tests\Integration\Providers\ApiHostProvider;
use Axytos\ECommerce\Tests\Integration\Providers\ApiKeyProvider;
use Axytos\ECommerce\Tests\Integration\Providers\FallbackModeConfiguration;
use Axytos\ECommerce\Tests\Integration\Providers\PaymentMethodConfiguration;
use Axytos\ECommerce\Tests\Integration\Providers\UserAgentInfoProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CheckoutClientIntegrationTest extends TestCase
{
    private CheckoutClientInterface $checkoutClient;

    public function setUp(): void
    {
        $this->checkoutClient = new AxytosECommerceClient(
            new ApiHostProvider(),
            new ApiKeyProvider(),
            new PaymentMethodConfiguration(),
            new FallbackModeConfiguration(),
            new UserAgentInfoProvider(),
            $this->createMock(LoggerAdapterInterface::class),
        );
    }

    public function test_getCreditCheckAgreementInfo(): void
    {
        $creditCheckAgreement = $this->checkoutClient->getCreditCheckAgreementInfo();

        $this->assertNotNull($creditCheckAgreement);
        $this->assertIsString($creditCheckAgreement);
        $this->assertNotEmpty($creditCheckAgreement);
    }

    public function test_getCreditCheckAgreementInfo_throws(): void
    {
        $this->expectException(CreditCheckAgreementLoadFailedException::class);

        /** @var ApiKeyProviderInterface&MockObject */
        $apiKeyProvider = $this->createMock(ApiKeyProviderInterface::class);
        $apiKeyProvider->method('getApiKey')->willReturn('invalid-api-key');

        $checkoutClient = new AxytosECommerceClient(
            new ApiHostProvider(),
            $apiKeyProvider,
            new PaymentMethodConfiguration(),
            new FallbackModeConfiguration(),
            new UserAgentInfoProvider(),
            $this->createMock(LoggerAdapterInterface::class),
        );

        $checkoutClient->getCreditCheckAgreementInfo();
    }
}
