<?php

namespace Axytos\ECommerce\Tests\Unit\OrderSync\Commands;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\OrderSync\Commands\ReportCreateInvoice;
use Axytos\ECommerce\OrderSync\ShopSystemOrderInterface;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ReportCreateInvoiceTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface&MockObject
     */
    private $invoiceClient;

    /**
     * @var ReportCreateInvoice
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

        $this->sut = new ReportCreateInvoice(
            $this->invoiceClient,
            $this->createMock(LoggerAdapterInterface::class),
            $this->createMock(ErrorReportingClientInterface::class)
        );
    }

    /**
     * @dataProvider execute_cases
     *
     * @param bool $hasCreateInvoiceReported
     * @param bool $hasBeenInvoiced
     * @param int  $reportCreateInvoiceInvocationCount
     *
     * @return void
     */
    #[DataProvider('execute_cases')]
    public function test_execute_reports_create_invoice($hasCreateInvoiceReported, $hasBeenInvoiced, $reportCreateInvoiceInvocationCount)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCreateInvoiceReported')->willReturn($hasCreateInvoiceReported);
        $shopSystemOrder->method('hasBeenInvoiced')->willReturn($hasBeenInvoiced);

        $reportData = $this->createMock(InvoiceOrderContextInterface::class);
        $shopSystemOrder->method('getCreateInvoiceReportData')->willReturn($reportData);

        $this->invoiceClient->expects($this->exactly($reportCreateInvoiceInvocationCount))->method('createInvoice')->with($reportData);

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     *
     * @param bool $hasCreateInvoiceReported
     * @param bool $hasBeenInvoiced
     * @param int  $reportCreateInvoiceInvocationCount
     *
     * @return void
     */
    #[DataProvider('execute_cases')]
    public function test_execute_saves_create_invoice_reported($hasCreateInvoiceReported, $hasBeenInvoiced, $reportCreateInvoiceInvocationCount)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCreateInvoiceReported')->willReturn($hasCreateInvoiceReported);
        $shopSystemOrder->method('hasBeenInvoiced')->willReturn($hasBeenInvoiced);

        $shopSystemOrder->expects($this->exactly($reportCreateInvoiceInvocationCount))->method('saveHasCreateInvoiceReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     *
     * @param bool $hasCreateInvoiceReported
     * @param bool $hasBeenInvoiced
     * @param int  $reportCreateInvoiceInvocationCount
     *
     * @return void
     */
    #[DataProvider('execute_cases')]
    public function test_execute_saves_create_invoice_reported_on_client_error($hasCreateInvoiceReported, $hasBeenInvoiced, $reportCreateInvoiceInvocationCount)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCreateInvoiceReported')->willReturn($hasCreateInvoiceReported);
        $shopSystemOrder->method('hasBeenInvoiced')->willReturn($hasBeenInvoiced);
        $this->invoiceClient->method('createInvoice')->willThrowException(new ApiException('', 400));

        $shopSystemOrder->expects($this->exactly($reportCreateInvoiceInvocationCount))->method('saveHasCreateInvoiceReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @return mixed[]
     */
    public static function execute_cases()
    {
        return [
            'already reported and invoiced     -> will not report' => [true, true, 0],
            'already reported and not invoiced -> will not report' => [true, false, 0],
            'not yet reported and invoiced     -> will report' => [false, true, 1],
            'not yet reported and not invoiced -> will not report' => [false, false, 0],
        ];
    }

    /**
     * @return void
     */
    public function test_execute_does_not_save_on_server_error()
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCreateInvoiceReported')->willReturn(false);
        $shopSystemOrder->method('hasBeenInvoiced')->willReturn(true);
        $this->invoiceClient->method('createInvoice')->willThrowException(new ApiException('', 500));

        $this->expectException(ApiException::class);
        $shopSystemOrder->expects($this->never())->method('saveHasCreateInvoiceReported');

        $this->sut->execute($shopSystemOrder);
    }
}
