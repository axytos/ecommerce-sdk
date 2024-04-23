<?php

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
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CheckoutClientIntegrationTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\Clients\Checkout\CheckoutClientInterface
     */
    private $checkoutClient;

    /**
     * @return void
     * @before
     */
    #[Before]
    public function beforeEach()
    {
        $this->checkoutClient = new AxytosECommerceClient(new ApiHostProvider(), new ApiKeyProvider(), new PaymentMethodConfiguration(), new FallbackModeConfiguration(), new UserAgentInfoProvider(), $this->createMock(LoggerAdapterInterface::class));
    }

    /**
     * @return void
     */
    public function test_getCreditCheckAgreementInfo()
    {
        $creditCheckAgreement = $this->checkoutClient->getCreditCheckAgreementInfo();

        $this->assertNotNull($creditCheckAgreement);
        $this->assertTrue(is_string($creditCheckAgreement));
        $this->assertNotEmpty($creditCheckAgreement);
    }

    /**
     * @return void
     */
    public function test_getCreditCheckAgreementInfo_throws()
    {
        $this->expectException(CreditCheckAgreementLoadFailedException::class);

        /** @var ApiKeyProviderInterface&MockObject */
        $apiKeyProvider = $this->createMock(ApiKeyProviderInterface::class);
        $apiKeyProvider->method('getApiKey')->willReturn('invalid-api-key');

        $checkoutClient = new AxytosECommerceClient(new ApiHostProvider(), $apiKeyProvider, new PaymentMethodConfiguration(), new FallbackModeConfiguration(), new UserAgentInfoProvider(), $this->createMock(LoggerAdapterInterface::class));

        $checkoutClient->getCreditCheckAgreementInfo();
    }
}
