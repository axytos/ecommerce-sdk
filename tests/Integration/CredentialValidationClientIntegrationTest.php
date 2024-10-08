<?php

namespace Axytos\ECommerce\Tests\Integration;

use Axytos\ECommerce\AxytosECommerceClient;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\Tests\Integration\Fakes\InvalidApiKeyProvider;
use Axytos\ECommerce\Tests\Integration\Providers\ApiHostProvider;
use Axytos\ECommerce\Tests\Integration\Providers\ApiKeyProvider;
use Axytos\ECommerce\Tests\Integration\Providers\FallbackModeConfiguration;
use Axytos\ECommerce\Tests\Integration\Providers\PaymentMethodConfiguration;
use Axytos\ECommerce\Tests\Integration\Providers\UserAgentInfoProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class CredentialValidationClientIntegrationTest extends TestCase
{
    /**
     * @dataProvider validateApiKeyDataProvider
     *
     * @param \Axytos\ECommerce\Abstractions\ApiKeyProviderInterface $apiKeyProvider
     * @param bool                                                   $isValid
     *
     * @return void
     */
    #[DataProvider('validateApiKeyDataProvider')]
    public function test_validate_api_key($apiKeyProvider, $isValid)
    {
        $client = new AxytosECommerceClient(new ApiHostProvider(), $apiKeyProvider, new PaymentMethodConfiguration(), new FallbackModeConfiguration(), new UserAgentInfoProvider(), $this->createMock(LoggerAdapterInterface::class));

        $this->assertEquals($isValid, $client->validateApiKey());
    }

    /**
     * @return mixed[]
     */
    public static function validateApiKeyDataProvider()
    {
        return [
            [self::createValidApiKeyProvider(), true],
            [self::createInvalidApiKeyProvider(), false],
        ];
    }

    /**
     * @return \Axytos\ECommerce\Abstractions\ApiKeyProviderInterface
     */
    private static function createValidApiKeyProvider()
    {
        return new ApiKeyProvider();
    }

    /**
     * @return \Axytos\ECommerce\Abstractions\ApiKeyProviderInterface
     */
    private static function createInvalidApiKeyProvider()
    {
        return new InvalidApiKeyProvider();
    }
}
