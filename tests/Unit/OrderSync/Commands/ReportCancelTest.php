<?php

namespace Axytos\ECommerce\Tests\Unit\OrderSync\Commands;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\OrderSync\Commands\ReportCancel;
use Axytos\ECommerce\OrderSync\ShopSystemOrderInterface;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ReportCancelTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface&MockObject
     */
    private $invoiceClient;

    /**
     * @var ReportCancel
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

        $this->sut = new ReportCancel(
            $this->invoiceClient,
            $this->createMock(LoggerAdapterInterface::class),
            $this->createMock(ErrorReportingClientInterface::class)
        );
    }

    /**
     * @dataProvider execute_cases
     *
     * @param bool $hasCancelReported
     * @param bool $hasBeenCanceled
     * @param int  $reportRefundInvocationCount
     *
     * @return void
     */
    #[DataProvider('execute_cases')]
    public function test_execute_reports_cancel($hasCancelReported, $hasBeenCanceled, $reportRefundInvocationCount)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCancelReported')->willReturn($hasCancelReported);
        $shopSystemOrder->method('hasBeenCanceled')->willReturn($hasBeenCanceled);

        $reportData = $this->createMock(InvoiceOrderContextInterface::class);
        $shopSystemOrder->method('getCancelReportData')->willReturn($reportData);

        $this->invoiceClient->expects($this->exactly($reportRefundInvocationCount))->method('cancelOrder')->with($reportData);

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     *
     * @param bool $hasCancelReported
     * @param bool $hasBeenCanceled
     * @param int  $reportRefundInvocationCount
     *
     * @return void
     */
    #[DataProvider('execute_cases')]
    public function test_execute_saves_cancel_reported($hasCancelReported, $hasBeenCanceled, $reportRefundInvocationCount)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCancelReported')->willReturn($hasCancelReported);
        $shopSystemOrder->method('hasBeenCanceled')->willReturn($hasBeenCanceled);

        $shopSystemOrder->expects($this->exactly($reportRefundInvocationCount))->method('saveHasCancelReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     *
     * @param bool $hasCancelReported
     * @param bool $hasBeenCanceled
     * @param int  $reportRefundInvocationCount
     *
     * @return void
     */
    #[DataProvider('execute_cases')]
    public function test_execute_saves_cancel_reported_on_client_error($hasCancelReported, $hasBeenCanceled, $reportRefundInvocationCount)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCancelReported')->willReturn($hasCancelReported);
        $shopSystemOrder->method('hasBeenCanceled')->willReturn($hasBeenCanceled);
        $this->invoiceClient->method('cancelOrder')->willThrowException(new ApiException('', 400));

        $shopSystemOrder->expects($this->exactly($reportRefundInvocationCount))->method('saveHasCancelReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @return mixed[]
     */
    public static function execute_cases()
    {
        return [
            'already reported and canceled     -> will not report' => [true, true, 0],
            'already reported and not canceled -> will not report' => [true, false, 0],
            'not yet reported and canceled     -> will report' => [false, true, 1],
            'not yet reported and not canceled -> will not report' => [false, false, 0],
        ];
    }

    /**
     * @return void
     */
    public function test_execute_does_not_save_on_server_error()
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCancelReported')->willReturn(false);
        $shopSystemOrder->method('hasBeenCanceled')->willReturn(true);
        $this->invoiceClient->method('cancelOrder')->willThrowException(new ApiException('', 500));

        $this->expectException(ApiException::class);
        $shopSystemOrder->expects($this->never())->method('saveHasCancelReported');

        $this->sut->execute($shopSystemOrder);
    }
}
