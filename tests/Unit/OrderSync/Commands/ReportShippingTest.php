<?php

namespace Axytos\ECommerce\Tests\Unit\OrderSync\Commands;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\OrderSync\Commands\ReportShipping;
use Axytos\ECommerce\OrderSync\ShopSystemOrderInterface;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ReportShippingTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface&MockObject
     */
    private $invoiceClient;

    /**
     * @var ReportShipping
     */
    private $sut;

    /**
     * @before
     *
     * @return void
     */
    #[Before]
    public function beforeEach()
    {
        $this->invoiceClient = $this->createMock(InvoiceClientInterface::class);

        $this->sut = new ReportShipping(
            $this->invoiceClient,
            $this->createMock(LoggerAdapterInterface::class),
            $this->createMock(ErrorReportingClientInterface::class)
        );
    }

    /**
     * @dataProvider execute_cases
     *
     * @param bool $hasShippingReported
     * @param bool $hasBeenShipped
     * @param int  $reportShippingInvocationCount
     *
     * @return void
     */
    #[DataProvider('execute_cases')]
    public function test_execute_reports_shipping($hasShippingReported, $hasBeenShipped, $reportShippingInvocationCount)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasShippingReported')->willReturn($hasShippingReported);
        $shopSystemOrder->method('hasBeenShipped')->willReturn($hasBeenShipped);

        $reportData = $this->createMock(InvoiceOrderContextInterface::class);
        $shopSystemOrder->method('getShippingReportData')->willReturn($reportData);

        $this->invoiceClient->expects($this->exactly($reportShippingInvocationCount))->method('reportShipping')->with($reportData);

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     *
     * @param bool $hasShippingReported
     * @param bool $hasBeenShipped
     * @param int  $reportShippingInvocationCount
     *
     * @return void
     */
    #[DataProvider('execute_cases')]
    public function test_execute_saves_shipping_reported($hasShippingReported, $hasBeenShipped, $reportShippingInvocationCount)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasShippingReported')->willReturn($hasShippingReported);
        $shopSystemOrder->method('hasBeenShipped')->willReturn($hasBeenShipped);

        $shopSystemOrder->expects($this->exactly($reportShippingInvocationCount))->method('saveHasShippingReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     *
     * @param bool $hasShippingReported
     * @param bool $hasBeenShipped
     * @param int  $reportShippingInvocationCount
     *
     * @return void
     */
    #[DataProvider('execute_cases')]
    public function test_execute_saves_shipping_reported_on_client_error($hasShippingReported, $hasBeenShipped, $reportShippingInvocationCount)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasShippingReported')->willReturn($hasShippingReported);
        $shopSystemOrder->method('hasBeenShipped')->willReturn($hasBeenShipped);
        $this->invoiceClient->method('reportShipping')->willThrowException(new ApiException('', 400));

        $shopSystemOrder->expects($this->exactly($reportShippingInvocationCount))->method('saveHasShippingReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @return void
     */
    public function test_execute_does_not_save_on_server_error()
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasShippingReported')->willReturn(false);
        $shopSystemOrder->method('hasBeenShipped')->willReturn(true);
        $this->invoiceClient->method('reportShipping')->willThrowException(new ApiException('', 500));

        $this->expectException(ApiException::class);
        $shopSystemOrder->expects($this->never())->method('saveHasShippingReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @return mixed[]
     */
    public static function execute_cases()
    {
        return [
            'already reported and shipped     -> will not report' => [true, true, 0],
            'already reported and not shipped -> will not report' => [true, false, 0],
            'not yet reported and shipped     -> will report' => [false, true, 1],
            'not yet reported and not shipped -> will not report' => [false, false, 0],
        ];
    }
}
