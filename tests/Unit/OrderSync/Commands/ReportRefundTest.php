<?php

namespace Axytos\ECommerce\Tests\Unit\OrderSync\Commands;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\OrderSync\Commands\ReportRefund;
use Axytos\ECommerce\OrderSync\ShopSystemOrderInterface;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ReportRefundTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface&MockObject
     */
    private $invoiceClient;

    /**
     * @var \Axytos\ECommerce\OrderSync\Commands\ReportRefund
     */
    private $sut;

    /**
     * @before
     * @return void
     */
    #[Before]
    public function beforeEach()
    {
        $this->invoiceClient = $this->createMock(InvoiceClientInterface::class);

        $this->sut = new ReportRefund(
            $this->invoiceClient,
            $this->createMock(LoggerAdapterInterface::class),
            $this->createMock(ErrorReportingClientInterface::class)
        );
    }

    /**
     * @dataProvider execute_cases
     * @param bool $hasCreateInvoiceReported
     * @param bool $hasRefundReported
     * @param bool $hasBeenRefunded
     * @param int $reportRefundInvocationCount
     * @return void
     */
    #[DataProvider('execute_cases')]
    public function test_execute_reports_refund($hasCreateInvoiceReported, $hasRefundReported, $hasBeenRefunded, $reportRefundInvocationCount)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCreateInvoiceReported')->willReturn($hasCreateInvoiceReported);
        $shopSystemOrder->method('hasRefundReported')->willReturn($hasRefundReported);
        $shopSystemOrder->method('hasBeenRefunded')->willReturn($hasBeenRefunded);

        $reportData = $this->createMock(InvoiceOrderContextInterface::class);
        $shopSystemOrder->method('getRefundReportData')->willReturn($reportData);

        $this->invoiceClient->expects($this->exactly($reportRefundInvocationCount))->method('refund')->with($reportData);

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     * @param bool $hasCreateInvoiceReported
     * @param bool $hasRefundReported
     * @param bool $hasBeenRefunded
     * @param int $reportRefundInvocationCount
     * @return void
     */
    #[DataProvider('execute_cases')]
    public function test_execute_saves_refund_reported($hasCreateInvoiceReported, $hasRefundReported, $hasBeenRefunded, $reportRefundInvocationCount)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCreateInvoiceReported')->willReturn($hasCreateInvoiceReported);
        $shopSystemOrder->method('hasRefundReported')->willReturn($hasRefundReported);
        $shopSystemOrder->method('hasBeenRefunded')->willReturn($hasBeenRefunded);

        $shopSystemOrder->expects($this->exactly($reportRefundInvocationCount))->method('saveHasRefundReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     * @param bool $hasCreateInvoiceReported
     * @param bool $hasRefundReported
     * @param bool $hasBeenRefunded
     * @param int $reportRefundInvocationCount
     * @return void
     */
    #[DataProvider('execute_cases')]
    public function test_execute_saves_refund_reported_on_client_error($hasCreateInvoiceReported, $hasRefundReported, $hasBeenRefunded, $reportRefundInvocationCount)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCreateInvoiceReported')->willReturn($hasCreateInvoiceReported);
        $shopSystemOrder->method('hasRefundReported')->willReturn($hasRefundReported);
        $shopSystemOrder->method('hasBeenRefunded')->willReturn($hasBeenRefunded);
        $this->invoiceClient->method('refund')->willThrowException(new ApiException("", 400));

        $shopSystemOrder->expects($this->exactly($reportRefundInvocationCount))->method('saveHasRefundReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @return void
     */
    public function test_execute_does_not_save_on_server_error()
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCreateInvoiceReported')->willReturn(true);
        $shopSystemOrder->method('hasRefundReported')->willReturn(false);
        $shopSystemOrder->method('hasBeenRefunded')->willReturn(true);
        $this->invoiceClient->method('refund')->willThrowException(new ApiException("", 500));

        $this->expectException(ApiException::class);
        $shopSystemOrder->expects($this->never())->method('saveHasRefundReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @return mixed[]
     */
    public static function execute_cases()
    {
        return [
            'invoice reported: already reported and refunded        -> will report' => [true, true, true, 0],
            'invoice reported: already reported and not refunded    -> will report' => [true, true, false, 0],
            'invoice reported: not yet reported and refunded        -> will report' => [true, false, true, 1],
            'invoice reported: not yet reported and not refunded    -> will report' => [true, false, false, 0],
            'no invoice reported: already reported and refunded     -> will report' => [false, true, true, 0],
            'no invoice reported: already reported and not refunded -> will report' => [false, true, false, 0],
            'no invoice reported: not yet reported and refunded     -> will report' => [false, false, true, 0],
            'no invoice reported: not yet reported and not refunded -> will report' => [false, false, false, 0],
        ];
    }
}
