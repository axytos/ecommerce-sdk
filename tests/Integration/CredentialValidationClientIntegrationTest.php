<?php

declare(strict_types=1);

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
     */
    public function test_validateApiKey(ApiKeyProviderInterface $apiKeyProvider, bool $isValid): void
    {
        $client = new AxytosECommerceClient(
            new ApiHostProvider(),
            $apiKeyProvider,
            new PaymentMethodConfiguration(),
            new FallbackModeConfiguration(),
            new UserAgentInfoProvider(),
            $this->createMock(LoggerAdapterInterface::class),
        );

        $this->assertEquals($isValid, $client->validateApiKey());
    }

    public function validateApiKeyDataProvider(): array
    {
        return [
            [$this->createValidApiKeyProvider(), true],
            [$this->createInvalidApiKeyProvider(), false],
        ];
    }

    private function createValidApiKeyProvider(): ApiKeyProviderInterface
    {
        return new ApiKeyProvider();
    }

    private function createInvalidApiKeyProvider(): ApiKeyProviderInterface
    {
        /** @var ApiKeyProviderInterface&MockObject */
        $mock = $this->createMock(ApiKeyProviderInterface::class);
        $mock->method('getApiKey')->willReturn('invalid-api-key');

        return $mock;
    }
}
