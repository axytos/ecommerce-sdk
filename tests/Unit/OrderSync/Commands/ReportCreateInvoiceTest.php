<?php

namespace Axytos\ECommerce\Tests\Unit\OrderSync\Commands;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\OrderSync\Commands\ReportCreateInvoice;
use Axytos\ECommerce\OrderSync\ShopSystemOrderInterface;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ReportCreateInvoiceTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface&MockObject
     */
    private $invoiceClient;

    /**
     * @var \Axytos\ECommerce\OrderSync\Commands\ReportCreateInvoice
     */
    private $sut;

    /**
     * @before
     * @return void
     */
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
     * @param bool $hasCreateInvoiceReported
     * @param bool $hasBeenInvoiced
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount $reportCreateInvoiceInvocations
     * @return void
     */
    public function test_execute_reports_create_invoice($hasCreateInvoiceReported, $hasBeenInvoiced, $reportCreateInvoiceInvocations)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCreateInvoiceReported')->willReturn($hasCreateInvoiceReported);
        $shopSystemOrder->method('hasBeenInvoiced')->willReturn($hasBeenInvoiced);

        $reportData = $this->createMock(InvoiceOrderContextInterface::class);
        $shopSystemOrder->method('getCreateInvoiceReportData')->willReturn($reportData);

        $this->invoiceClient->expects($reportCreateInvoiceInvocations)->method('createInvoice')->with($reportData);

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     * @param bool $hasCreateInvoiceReported
     * @param bool $hasBeenInvoiced
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount $reportCreateInvoiceInvocations
     * @return void
     */
    public function test_execute_saves_create_invoice_reported($hasCreateInvoiceReported, $hasBeenInvoiced, $reportCreateInvoiceInvocations)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCreateInvoiceReported')->willReturn($hasCreateInvoiceReported);
        $shopSystemOrder->method('hasBeenInvoiced')->willReturn($hasBeenInvoiced);

        $shopSystemOrder->expects($reportCreateInvoiceInvocations)->method('saveHasCreateInvoiceReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     * @param bool $hasCreateInvoiceReported
     * @param bool $hasBeenInvoiced
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount $reportCreateInvoiceInvocations
     * @return void
     */
    public function test_execute_saves_create_invoice_reported_on_client_error($hasCreateInvoiceReported, $hasBeenInvoiced, $reportCreateInvoiceInvocations)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasCreateInvoiceReported')->willReturn($hasCreateInvoiceReported);
        $shopSystemOrder->method('hasBeenInvoiced')->willReturn($hasBeenInvoiced);
        $this->invoiceClient->method('createInvoice')->willThrowException(new ApiException("", 400));

        $shopSystemOrder->expects($reportCreateInvoiceInvocations)->method('saveHasCreateInvoiceReported');

        $this->sut->execute($shopSystemOrder);
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
        $this->invoiceClient->method('createInvoice')->willThrowException(new ApiException("", 500));

        $this->expectException(ApiException::class);
        $shopSystemOrder->expects($this->never())->method('saveHasCreateInvoiceReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @return mixed[]
     */
    public function execute_cases()
    {
        return [
            'already reported and invoiced     -> will not report' => [true, true, $this->never()],
            'already reported and not invoiced -> will not report' => [true, false, $this->never()],
            'not yet reported and invoiced     -> will report' => [false, true, $this->once()],
            'not yet reported and not invoiced -> will not report' => [false, false, $this->never()],
        ];
    }
}
