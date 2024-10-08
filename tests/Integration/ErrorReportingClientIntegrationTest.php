<?php

namespace Axytos\ECommerce\Tests\Integration;

use Axytos\ECommerce\AxytosECommerceClient;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\Tests\Integration\Providers\ApiHostProvider;
use Axytos\ECommerce\Tests\Integration\Providers\ApiKeyProvider;
use Axytos\ECommerce\Tests\Integration\Providers\FallbackModeConfiguration;
use Axytos\ECommerce\Tests\Integration\Providers\PaymentMethodConfiguration;
use Axytos\ECommerce\Tests\Integration\Providers\UserAgentInfoProvider;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ErrorReportingClientIntegrationTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface
     */
    private $errorReportingClient;

    /**
     * @return void
     *
     * @before
     */
    #[Before]
    public function beforeEach()
    {
        $this->errorReportingClient = new AxytosECommerceClient(new ApiHostProvider(), new ApiKeyProvider(), new PaymentMethodConfiguration(), new FallbackModeConfiguration(), new UserAgentInfoProvider(), $this->createMock(LoggerAdapterInterface::class));
    }

    /**
     * @dataProvider reportErrorDataProvider
     *
     * @param string $message
     *
     * @return void
     */
    #[DataProvider('reportErrorDataProvider')]
    public function test_report_error($message)
    {
        try {
            throw new \Exception($message);
        } catch (\Throwable $th) {
            $this->errorReportingClient->reportError($th);
            $this->assertTrue(true);
        } catch (\Exception $th) {  // @phpstan-ignore-line / php5 compatibility
            $this->errorReportingClient->reportError($th);
            $this->assertTrue(true);
        }
    }

    /**
     * @return mixed[]
     */
    public static function reportErrorDataProvider()
    {
        return [
            ['Error Message'],
            [''], // no error message
        ];
    }
}
