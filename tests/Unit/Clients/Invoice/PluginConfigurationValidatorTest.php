<?php

namespace Axytos\ECommerce\Tests\Unit\Clients\Invoice;

use Axytos\ECommerce\Abstractions\ApiHostProviderInterface;
use Axytos\ECommerce\Abstractions\ApiKeyProviderInterface;
use Axytos\ECommerce\Clients\Invoice\PluginConfigurationValidator;
use Exception;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class PluginConfigurationValidatorTest extends TestCase
{
    /** @var ApiHostProviderInterface&MockObject */
    private $apiHostProvider;


    /** @var ApiKeyProviderInterface&MockObject */
    private $apiKeyProvider;

    /**
     * @var \Axytos\ECommerce\Clients\Invoice\PluginConfigurationValidator
     */
    private $sut;

    /**
     * @return void
     * @before
     */
    public function beforeEach()
    {
        $this->apiHostProvider = $this->createMock(ApiHostProviderInterface::class);
        $this->apiKeyProvider = $this->createMock(ApiKeyProviderInterface::class);

        $this->sut = new PluginConfigurationValidator(
            $this->apiHostProvider,
            $this->apiKeyProvider
        );
    }

    /**
     * @dataProvider dataProvider_test_isInvalid
     * @param string $apiHost
     * @param string $apiKey
     * @param bool $expectedOutcome
     * @return void
     */
    public function test_isInvalid(
        $apiHost,
        $apiKey,
        $expectedOutcome
    ) {
        $this->apiHostProvider->method('getApiHost')->willReturn($apiHost);
        $this->apiKeyProvider->method('getApiKey')->willReturn($apiKey);

        $actual = $this->sut->isInvalid();

        $this->assertEquals($expectedOutcome, $actual);
    }

    /**
     * @return mixed[]
     */
    public function dataProvider_test_isInvalid()
    {
        return [
            ['','', true],
            ['','apiKey', true],
            ['apiHost','', true],
            ['apiHost','apiKey', false],
        ];
    }

    /**
     * @return void
     */
    public function test_isInvalid_returns_true_when_ApiHostProvider_throws()
    {
        $this->apiHostProvider->method('getApiHost')->willThrowException(new Exception());

        $this->assertTrue($this->sut->isInvalid());
    }

    /**
     * @return void
     */
    public function test_isInvalid_returns_true_when_ApiKeyProvider_throws()
    {
        $this->apiKeyProvider->method('getApiKey')->willThrowException(new Exception());

        $this->assertTrue($this->sut->isInvalid());
    }
}
