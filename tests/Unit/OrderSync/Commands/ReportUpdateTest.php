<?php

namespace Axytos\ECommerce\Tests\Unit\OrderSync\Commands;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\OrderSync\Commands\ReportUpdate;
use Axytos\ECommerce\OrderSync\ShopSystemOrderInterface;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ReportUpdateTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface&MockObject
     */
    private $invoiceClient;

    /**
     * @var \Axytos\ECommerce\OrderSync\Commands\ReportUpdate
     */
    private $sut;

    /**
     * @before
     * @return void
     */
    public function beforeEach()
    {
        $this->invoiceClient = $this->createMock(InvoiceClientInterface::class);

        $this->sut = new ReportUpdate(
            $this->invoiceClient,
            $this->createMock(LoggerAdapterInterface::class),
            $this->createMock(ErrorReportingClientInterface::class)
        );
    }

    /**
     * @dataProvider execute_cases
     * @param bool $hasBasketUpdates
     * @param bool $hasBeenCanceled
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount $updateOrderInvocations
     * @return void
     */
    public function test_execute_reports_update($hasBasketUpdates, $hasBeenCanceled, $updateOrderInvocations)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasBasketUpdates')->willReturn($hasBasketUpdates);
        $shopSystemOrder->method('hasBeenCanceled')->willReturn($hasBeenCanceled);

        $reportData = $this->createMock(InvoiceOrderContextInterface::class);
        $shopSystemOrder->method('getBasketUpdateReportData')->willReturn($reportData);

        $this->invoiceClient
            ->expects($updateOrderInvocations)
            ->method('updateOrder')
            ->with($reportData);

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     * @param bool $hasBasketUpdates
     * @param bool $hasBeenCanceled
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount $saveBasketUpdatesReportedInvocations
     * @return void
     */
    public function test_execute_saves_reported_basket_changes($hasBasketUpdates, $hasBeenCanceled, $saveBasketUpdatesReportedInvocations)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasBasketUpdates')->willReturn($hasBasketUpdates);
        $shopSystemOrder->method('hasBeenCanceled')->willReturn($hasBeenCanceled);

        $shopSystemOrder
            ->expects($saveBasketUpdatesReportedInvocations)
            ->method('saveBasketUpdatesReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     * @param bool $hasBasketUpdates
     * @param bool $hasBeenCanceled
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount $saveBasketUpdatesReportedInvocations
     * @return void
     */
    public function test_execute_saves_reported_basket_changes_on_client_error($hasBasketUpdates, $hasBeenCanceled, $saveBasketUpdatesReportedInvocations)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasBasketUpdates')->willReturn($hasBasketUpdates);
        $shopSystemOrder->method('hasBeenCanceled')->willReturn($hasBeenCanceled);
        $this->invoiceClient->method('updateOrder')->willThrowException(new ApiException("", 400));

        $shopSystemOrder
            ->expects($saveBasketUpdatesReportedInvocations)
            ->method('saveBasketUpdatesReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @return void
     */
    public function test_execute_does_not_save_on_server_error()
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasBasketUpdates')->willReturn(true);
        $shopSystemOrder->method('hasBeenCanceled')->willReturn(false);
        $this->invoiceClient->method('updateOrder')->willThrowException(new ApiException("", 500));

        $this->expectException(ApiException::class);
        $shopSystemOrder->expects($this->never())->method('saveBasketUpdatesReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @return mixed[]
     */
    public function execute_cases()
    {
        return [
            'new basket changes and not canceled -> will report' => [true, false, $this->once()],
            'no new basket changes and not canceled -> will not report' => [false, false, $this->never()],
            'new basket changes and canceled -> will not report' => [true, true, $this->never()],
            'no new basket changes and canceled -> will not report' => [false, true, $this->never()],
        ];
    }
}
