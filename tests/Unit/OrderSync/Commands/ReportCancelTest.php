<?php

namespace Axytos\ECommerce\Tests\Unit\OrderSync\Commands;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\OrderSync\Commands\ReportCancel;
use Axytos\ECommerce\OrderSync\ShopSystemOrderInterface;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ReportCancelTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface&MockObject
     */
    private $invoiceClient;

    /**
     * @var \Axytos\ECommerce\OrderSync\Commands\ReportCancel
     */
    private $sut;

    /**
     * @before
     * @return void
     */
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
     * @param bool $hasCancelReported
     * @param bool $hasBeenCanceled
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount $reportRefundInvocations
     * @return void
     */
    public function test_execute_reports_cancel($hasCancelReported, $hasBeenCanceled, $reportRefundInvocations)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCancelReported')->willReturn($hasCancelReported);
        $shopSystemOrder->method('hasBeenCanceled')->willReturn($hasBeenCanceled);

        $reportData = $this->createMock(InvoiceOrderContextInterface::class);
        $shopSystemOrder->method('getCancelReportData')->willReturn($reportData);

        $this->invoiceClient->expects($reportRefundInvocations)->method('cancelOrder')->with($reportData);

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     * @param bool $hasCancelReported
     * @param bool $hasBeenCanceled
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount $reportRefundInvocations
     * @return void
     */
    public function test_execute_saves_cancel_reported($hasCancelReported, $hasBeenCanceled, $reportRefundInvocations)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCancelReported')->willReturn($hasCancelReported);
        $shopSystemOrder->method('hasBeenCanceled')->willReturn($hasBeenCanceled);

        $shopSystemOrder->expects($reportRefundInvocations)->method('saveHasCancelReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     * @param bool $hasCancelReported
     * @param bool $hasBeenCanceled
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount $reportRefundInvocations
     * @return void
     */
    public function test_execute_saves_cancel_reported_on_client_error($hasCancelReported, $hasBeenCanceled, $reportRefundInvocations)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCancelReported')->willReturn($hasCancelReported);
        $shopSystemOrder->method('hasBeenCanceled')->willReturn($hasBeenCanceled);
        $this->invoiceClient->method('cancelOrder')->willThrowException(new ApiException("", 400));

        $shopSystemOrder->expects($reportRefundInvocations)->method('saveHasCancelReported');

        $this->sut->execute($shopSystemOrder);
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
        $this->invoiceClient->method('cancelOrder')->willThrowException(new ApiException("", 500));

        $this->expectException(ApiException::class);
        $shopSystemOrder->expects($this->never())->method('saveHasCancelReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @return mixed[]
     */
    public function execute_cases()
    {
        return [
            'already reported and canceled     -> will not report' => [true, true, $this->never()],
            'already reported and not canceled -> will not report' => [true, false, $this->never()],
            'not yet reported and canceled     -> will report' => [false, true, $this->once()],
            'not yet reported and not canceled -> will not report' => [false, false, $this->never()],
        ];
    }
}
