<?php

namespace Axytos\ECommerce\Tests\Integration;

use Axytos\ECommerce\Abstractions\ApiKeyProviderInterface;
use Axytos\ECommerce\AxytosECommerceClient;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\Tests\Integration\Providers\ApiHostProvider;
use Axytos\ECommerce\Tests\Integration\Providers\ApiKeyProvider;
use Axytos\ECommerce\Tests\Integration\Providers\FallbackModeConfiguration;
use Axytos\ECommerce\Tests\Integration\Providers\PaymentMethodConfiguration;
use Axytos\ECommerce\Tests\Integration\Providers\UserAgentInfoProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CredentialValidationClientIntegrationTest extends TestCase
{
    /**
     * @dataProvider validateApiKeyDataProvider
     * @param \Axytos\ECommerce\Abstractions\ApiKeyProviderInterface $apiKeyProvider
     * @param bool $isValid
     * @return void
     */
    public function test_validateApiKey($apiKeyProvider, $isValid)
    {
        $client = new AxytosECommerceClient(new ApiHostProvider(), $apiKeyProvider, new PaymentMethodConfiguration(), new FallbackModeConfiguration(), new UserAgentInfoProvider(), $this->createMock(LoggerAdapterInterface::class));

        $this->assertEquals($isValid, $client->validateApiKey());
    }

    /**
     * @return mixed[]
     */
    public function validateApiKeyDataProvider()
    {
        return [
            [$this->createValidApiKeyProvider(), true],
            [$this->createInvalidApiKeyProvider(), false],
        ];
    }

    /**
     * @return \Axytos\ECommerce\Abstractions\ApiKeyProviderInterface
     */
    private function createValidApiKeyProvider()
    {
        return new ApiKeyProvider();
    }

    /**
     * @return \Axytos\ECommerce\Abstractions\ApiKeyProviderInterface
     */
    private function createInvalidApiKeyProvider()
    {
        /** @var ApiKeyProviderInterface&MockObject */
        $mock = $this->createMock(ApiKeyProviderInterface::class);
        $mock->method('getApiKey')->willReturn('invalid-api-key');

        return $mock;
    }
}
