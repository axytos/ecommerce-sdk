<?php

namespace Axytos\ECommerce\Tests\Integration;

use Axytos\ECommerce\AxytosECommerceClient;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\Tests\Integration\Fakes\UntrimmedApiHostProvider;
use Axytos\ECommerce\Tests\Integration\Fakes\UntrimmedApiKeyProvider;
use Axytos\ECommerce\Tests\Integration\Providers\ApiHostProvider;
use Axytos\ECommerce\Tests\Integration\Providers\ApiKeyProvider;
use Axytos\ECommerce\Tests\Integration\Providers\FallbackModeConfiguration;
use Axytos\ECommerce\Tests\Integration\Providers\PaymentMethodConfiguration;
use Axytos\ECommerce\Tests\Integration\Providers\UserAgentInfoProvider;
use PHPUnit\Framework\TestCase;

class UntrimmedApiConfigurationTest extends TestCase
{
    /**
     * @return void
     */
    public function test_getCreditCheckAgreementInfo_works_with_untrimmed_api_host_config()
    {
        $checkoutClient = new AxytosECommerceClient(
            new UntrimmedApiHostProvider(new ApiHostProvider()),
            new ApiKeyProvider(),
            new PaymentMethodConfiguration(),
            new FallbackModeConfiguration(),
            new UserAgentInfoProvider(),
            $this->createMock(LoggerAdapterInterface::class)
        );

        $creditCheckAgreement = $checkoutClient->getCreditCheckAgreementInfo();

        $this->assertNotNull($creditCheckAgreement);
        $this->assertTrue(is_string($creditCheckAgreement));
        $this->assertNotEmpty($creditCheckAgreement);
    }

    /**
     * @return void
     */
    public function test_getCreditCheckAgreementInfo_works_with_untrimmed_api_key_config()
    {
        $checkoutClient = new AxytosECommerceClient(
            new ApiHostProvider(),
            new UntrimmedApiKeyProvider(new ApiKeyProvider()),
            new PaymentMethodConfiguration(),
            new FallbackModeConfiguration(),
            new UserAgentInfoProvider(),
            $this->createMock(LoggerAdapterInterface::class)
        );

        $creditCheckAgreement = $checkoutClient->getCreditCheckAgreementInfo();

        $this->assertNotNull($creditCheckAgreement);
        $this->assertTrue(is_string($creditCheckAgreement));
        $this->assertNotEmpty($creditCheckAgreement);
    }

    /**
     * @return void
     */
    public function test_getCreditCheckAgreementInfo_works_with_untrimmed_api_config()
    {
        $checkoutClient = new AxytosECommerceClient(
            new UntrimmedApiHostProvider(new ApiHostProvider()),
            new UntrimmedApiKeyProvider(new ApiKeyProvider()),
            new PaymentMethodConfiguration(),
            new FallbackModeConfiguration(),
            new UserAgentInfoProvider(),
            $this->createMock(LoggerAdapterInterface::class)
        );

        $creditCheckAgreement = $checkoutClient->getCreditCheckAgreementInfo();

        $this->assertNotNull($creditCheckAgreement);
        $this->assertTrue(is_string($creditCheckAgreement));
        $this->assertNotEmpty($creditCheckAgreement);
    }
}
