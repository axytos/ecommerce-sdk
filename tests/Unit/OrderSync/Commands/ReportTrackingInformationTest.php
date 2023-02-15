<?php

namespace Axytos\ECommerce\Tests\Unit\OrderSync\Commands;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\OrderSync\Commands\ReportTrackingInformation;
use Axytos\ECommerce\OrderSync\ShopSystemOrderInterface;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ReportTrackingInformationTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface&MockObject
     */
    private $invoiceClient;

    /**
     * @var \Axytos\ECommerce\OrderSync\Commands\ReportTrackingInformation
     */
    private $sut;

    /**
     * @before
     * @return void
     */
    public function beforeEach()
    {
        $this->invoiceClient = $this->createMock(InvoiceClientInterface::class);

        $this->sut = new ReportTrackingInformation(
            $this->invoiceClient,
            $this->createMock(LoggerAdapterInterface::class),
            $this->createMock(ErrorReportingClientInterface::class)
        );
    }

    /**
     * @dataProvider execute_cases
     * @param bool $hasNewTrackingInformation
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount $reportNewTrackingInformationInvocations
     * @return void
     */
    public function test_execute_reports_new_tracking_information($hasNewTrackingInformation, $reportNewTrackingInformationInvocations)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasNewTrackingInformation')->willReturn($hasNewTrackingInformation);

        $trackingInformation = $this->createTrackingInformation();
        $shopSystemOrder->method('getNewTrackingInformationReportData')->willReturn($trackingInformation);

        $this->invoiceClient->expects($reportNewTrackingInformationInvocations)->method('trackingInformation')->with($trackingInformation);

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     * @param bool $hasNewTrackingInformation
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount $reportNewTrackingInformationInvocations
     * @return void
     */
    public function test_execute_saves_reported_new_tracking_information($hasNewTrackingInformation, $reportNewTrackingInformationInvocations)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasNewTrackingInformation')->willReturn($hasNewTrackingInformation);
        $shopSystemOrder->method('getNewTrackingInformationReportData')->willReturn($this->createTrackingInformation());

        $shopSystemOrder->expects($reportNewTrackingInformationInvocations)->method('saveNewTrackingInformation');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @dataProvider execute_cases
     * @param bool $hasNewTrackingInformation
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount $reportNewTrackingInformationInvocations
     * @return void
     */
    public function test_execute_saves_reported_new_tracking_information_on_client_error($hasNewTrackingInformation, $reportNewTrackingInformationInvocations)
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasNewTrackingInformation')->willReturn($hasNewTrackingInformation);
        $shopSystemOrder->method('getNewTrackingInformationReportData')->willReturn($this->createTrackingInformation());
        $this->invoiceClient->method('trackingInformation')->willThrowException(new ApiException("", 400));

        $shopSystemOrder->expects($reportNewTrackingInformationInvocations)->method('saveNewTrackingInformation');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @return void
     */
    public function test_execute_does_not_save_on_server_error()
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $shopSystemOrder->method('hasNewTrackingInformation')->willReturn(true);
        $shopSystemOrder->method('getNewTrackingInformationReportData')->willReturn($this->createTrackingInformation());
        $this->invoiceClient->method('trackingInformation')->willThrowException(new ApiException("", 500));

        $this->expectException(ApiException::class);
        $shopSystemOrder->expects($this->never())->method('saveNewTrackingInformation');

        $this->sut->execute($shopSystemOrder);
    }

    /**
     * @return mixed[]
     */
    public function execute_cases()
    {
        return [
            'new tracking information -> will report' => [true, $this->once()],
            'not new tracking information -> will not report' => [false, $this->never()],
        ];
    }

    /**
     * @return InvoiceOrderContextInterface
     */
    private function createTrackingInformation()
    {
        /** @var InvoiceOrderContextInterface&MockObject */
        $trackingInformation = $this->createMock(InvoiceOrderContextInterface::class);
        $trackingInformation->method('getTrackingIds')->willReturn(['TrackingCode']);
        $trackingInformation->method('getLogistician')->willReturn('Logistician');

        return $trackingInformation;
    }
}
