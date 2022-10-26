<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Integration;

use Axytos\ECommerce\AxytosECommerceClient;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\Tests\Integration\Providers\ApiHostProvider;
use Axytos\ECommerce\Tests\Integration\Providers\ApiKeyProvider;
use Axytos\ECommerce\Tests\Integration\Providers\FallbackModeConfiguration;
use Axytos\ECommerce\Tests\Integration\Providers\PaymentMethodConfiguration;
use Axytos\ECommerce\Tests\Integration\Providers\UserAgentInfoProvider;
use PHPUnit\Framework\TestCase;

class ErrorReportingClientIntegrationTest extends TestCase
{
    private ErrorReportingClientInterface $errorReportingClient;

    public function setUp(): void
    {
        $this->errorReportingClient = new AxytosECommerceClient(
            new ApiHostProvider(),
            new ApiKeyProvider(),
            new PaymentMethodConfiguration(),
            new FallbackModeConfiguration(),
            new UserAgentInfoProvider(),
            $this->createMock(LoggerAdapterInterface::class),
        );
    }

    /**
     * @dataProvider reportErrorDataProvider
     */
    public function test_reportError(string $message): void
    {
        try {
            throw new \Exception($message);
        } catch (\Throwable $th) {
            $this->errorReportingClient->reportError($th);
            $this->assertTrue(true);
        }
    }

    public function reportErrorDataProvider(): array
    {
        return [
            ['Error Message'],
            [''] // no error message
        ];
    }
}
