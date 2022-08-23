<?php declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Clients\Invoice;

use Axytos\ECommerce\Abstractions\ApiHostProviderInterface;
use Axytos\ECommerce\Abstractions\ApiKeyProviderInterface;
use Axytos\ECommerce\Clients\Invoice\PluginConfigurationValidator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class PluginConfigurationValidatorTest extends TestCase
{
    /** @var ApiHostProviderInterface&MockObject */
    private ApiHostProviderInterface $apiHostProvider;

    
    /** @var ApiKeyProviderInterface&MockObject */
    private ApiKeyProviderInterface $apiKeyProvider;

    private PluginConfigurationValidator $sut;

    public function setUp(): void
    {
        $this->apiHostProvider = $this->createMock(ApiHostProviderInterface::class);
        $this->apiKeyProvider = $this->createMock(ApiKeyProviderInterface::class);
        
        $this->sut = new PluginConfigurationValidator(
            $this->apiHostProvider,
            $this->apiKeyProvider);
    }

    /**
     * @dataProvider dataProvider_test_isInvalid
     */
    public function test_isInvalid(
        string $apiHost,
        string $apiKey,
        bool $expectedOutcome): void
    {
        $this->apiHostProvider->method('getApiHost')->willReturn($apiHost);
        $this->apiKeyProvider->method('getApiKey')->willReturn($apiKey);

        $actual = $this->sut->isInvalid();

        $this->assertEquals($expectedOutcome, $actual);
    }

    public function dataProvider_test_isInvalid(): array
    {
        return [
            ['','', true],
            ['','apiKey', true],
            ['apiHost','', true],
            ['apiHost','apiKey', false],
        ];
    }
}