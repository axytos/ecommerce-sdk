<?php

namespace Axytos\ECommerce\Tests\Unit\OrderSync\Commands;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\OrderSync\Commands\ReportUpdate;
use Axytos\ECommerce\OrderSync\ShopSystemOrderInterface;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ReportUpdateTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface&MockObject
     */
    private $invoiceClient;

    /**
     * @var ReportUpdate
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

        $this->sut = new ReportUpdate(
            $this->invoiceClient,
            $this->createMock(LoggerAdapterInterface::class),
            $this->createMock(ErrorReportingClientInterface::class)
        );
    }

    /**
     * @dataProvider execute_cases
     *
     * @param bool $hasBasketUpdates
     * @param bool $hasBeenCanceled
     * @param int  $updateOrderInvocationCount
     *
     * @return void
     */
    #[DataProvider('execute_cases')]
    public function test_execute_reports_update($hasBasketUpdates, $hasBeenCanceled, $updateOrderInvocationCount)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasBasketUpdates')->willReturn($hasBasketUpdates);
        $shopSystemOrder->method('hasBeenCanceled')->willReturn($hasBeenCanceled);

        $reportData = $this->createMock(InvoiceOrderContextInterface::class);
        $shopSystemOrder->method('getBasketUpdateReportData')->willReturn($reportData);

        $this->invoiceClient
            ->expects($this->exactly($updateOrderInvocationCount))
            ->method('updateOrder')
            ->with($reportData)
        ;

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     *
     * @param bool $hasBasketUpdates
     * @param bool $hasBeenCanceled
     * @param int  $saveBasketUpdatesReportedInvocationCount
     *
     * @return void
     */
    #[DataProvider('execute_cases')]
    public function test_execute_saves_reported_basket_changes($hasBasketUpdates, $hasBeenCanceled, $saveBasketUpdatesReportedInvocationCount)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasBasketUpdates')->willReturn($hasBasketUpdates);
        $shopSystemOrder->method('hasBeenCanceled')->willReturn($hasBeenCanceled);

        $shopSystemOrder
            ->expects($this->exactly($saveBasketUpdatesReportedInvocationCount))
            ->method('saveBasketUpdatesReported')
        ;

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     *
     * @param bool $hasBasketUpdates
     * @param bool $hasBeenCanceled
     * @param int  $saveBasketUpdatesReportedInvocationCount
     *
     * @return void
     */
    #[DataProvider('execute_cases')]
    public function test_execute_saves_reported_basket_changes_on_client_error($hasBasketUpdates, $hasBeenCanceled, $saveBasketUpdatesReportedInvocationCount)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasBasketUpdates')->willReturn($hasBasketUpdates);
        $shopSystemOrder->method('hasBeenCanceled')->willReturn($hasBeenCanceled);
        $this->invoiceClient->method('updateOrder')->willThrowException(new ApiException('', 400));

        $shopSystemOrder
            ->expects($this->exactly($saveBasketUpdatesReportedInvocationCount))
            ->method('saveBasketUpdatesReported')
        ;

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
        $this->invoiceClient->method('updateOrder')->willThrowException(new ApiException('', 500));

        $this->expectException(ApiException::class);
        $shopSystemOrder->expects($this->never())->method('saveBasketUpdatesReported');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @return mixed[]
     */
    public static function execute_cases()
    {
        return [
            'new basket changes and not canceled -> will report' => [true, false, 1],
            'no new basket changes and not canceled -> will not report' => [false, false, 0],
            'new basket changes and canceled -> will not report' => [true, true, 0],
            'no new basket changes and canceled -> will not report' => [false, true, 0],
        ];
    }
}
