<?php

namespace Axytos\ECommerce\Tests\Integration\OrderSync;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\OrderSync\OrderSyncItemFactory;
use Axytos\ECommerce\OrderSync\OrderSyncItemRepository;
use Axytos\ECommerce\OrderSync\OrderSyncWorker;
use Axytos\ECommerce\OrderSync\ShopSystemOrderRepositoryInterface;
use Axytos\ECommerce\Tests\Integration\OrderSync\Mocks\InvoiceClientMock;
use Axytos\ECommerce\Tests\Integration\OrderSync\Mocks\InvoiceOrderContextMock;
use Axytos\ECommerce\Tests\Integration\OrderSync\Mocks\ShopSystemOrderMock;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

include_once __DIR__ . '/Mocks/InvoiceClientMock.php';
include_once __DIR__ . '/Mocks/InvoiceOrderContextMock.php';
include_once __DIR__ . '/Mocks/ShopSystemOrderMock.php';

/**
 * @internal
 */
class OrderSyncWorkerIntegratedTestCase extends TestCase
{
    /**
     * @var InvoiceClientMock
     */
    private $invoiceClient;

    /**
     * @var \Axytos\ECommerce\OrderSync\ShopSystemOrderRepositoryInterface&MockObject
     */
    private $shopSystemOrderRepository;

    /**
     * @var OrderSyncWorker
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
        $this->invoiceClient = new InvoiceClientMock();
        $this->shopSystemOrderRepository = $this->createMock(ShopSystemOrderRepositoryInterface::class);

        $this->sut = new OrderSyncWorker(
            new OrderSyncItemRepository(
                $this->shopSystemOrderRepository,
                new OrderSyncItemFactory(
                    $this->invoiceClient,
                    $this->createMock(ErrorReportingClientInterface::class),
                    $this->createMock(LoggerAdapterInterface::class)
                )
            ),
            $this->createMock(LoggerAdapterInterface::class)
        );
    }

    /**
     * @param array<string,array<string,bool>>[] $configs
     *
     * @return void
     */
    protected function executeTestCases($configs)
    {
        /** @var ShopSystemOrderMock[]&MockObject[] */
        $shopSystemOrders = [];

        foreach ($configs as $key => $config) {
            $shopSystemOrders[$key] = new ShopSystemOrderMock($key, $config);
        }

        $this->shopSystemOrderRepository->method('getOrdersToUpdate')->willReturn($shopSystemOrders);
        $this->shopSystemOrderRepository->method('getOrdersToSync')->willReturn($shopSystemOrders);

        $this->sut->sync();

        /** @var array<string,ShopSystemOrderMock[]> */
        $callRecords = [];

        foreach ($this->invoiceClient->getCallRecords() as $key => $callRecord) {
            $callRecords[$key] = array_map(function ($context) {
                if ($context instanceof InvoiceOrderContextMock) {
                    return $context->getShopSystemOrderMock();
                }

                return null;
            }, $callRecord);
        }

        foreach ($shopSystemOrders as $key => $shopSystemOrder) {
            $config = $shopSystemOrder->getTestConfig();

            // Cancel
            $this->assertEquals(
                $config['expected']['reportCancel'],
                in_array($shopSystemOrder, $callRecords['cancelOrder'], true),
                "Send Cancel Report has unexpected outcome for configured order at index {$key}."
            );
            $this->assertEquals(
                $config['expected']['reportCancel'],
                $config['actual']['saveHasCancelReported'],
                "Save Cancel Report has unexpected outcome for configured order at index {$key}."
            );

            // Create Invoice
            $this->assertEquals(
                $config['expected']['reportCreateInvoice'],
                in_array($shopSystemOrder, $callRecords['createInvoice'], true),
                "Refund Create Invoice has unexpected outcome for configured order at index {$key}."
            );
            $this->assertEquals(
                $config['expected']['reportCreateInvoice'],
                $config['actual']['saveHasCreateInvoiceReported'],
                "Save Create Invoice Reported has unexpected outcome for configured order at index {$key}."
            );

            // Refund
            $this->assertEquals(
                $config['expected']['reportRefund'],
                in_array($shopSystemOrder, $callRecords['refund'], true),
                "Send Refund Report has unexpected outcome for configured order at index {$key}."
            );
            $this->assertEquals(
                $config['expected']['reportRefund'],
                $config['actual']['saveHasRefundReported'],
                "Save Refund Reported has unexpected outcome for configured order at index {$key}."
            );

            // Shipping
            $this->assertEquals(
                $config['expected']['reportShipping'],
                in_array($shopSystemOrder, $callRecords['reportShipping'], true),
                "Send Shipping Report has unexpected outcome for configured order at index {$key}."
            );
            $this->assertEquals(
                $config['expected']['reportShipping'],
                $config['actual']['saveHasShippingReported'],
                "Save Shipping Reported has unexpected outcome for configured order at index {$key}."
            );

            // Tracking Information
            $this->assertEquals(
                $config['expected']['reportTrackingInformation'],
                in_array($shopSystemOrder, $callRecords['trackingInformation'], true),
                "Send Tracking Information Report has unexpected outcome for configured order at index {$key}."
            );
            $this->assertEquals(
                $config['expected']['reportTrackingInformation'],
                $config['actual']['saveNewTrackingInformation'],
                "Save Tracking Information Reported has unexpected outcome for configured order at index {$key}."
            );

            // Order Update
            $this->assertEquals(
                $config['expected']['reportUpdate'],
                in_array($shopSystemOrder, $callRecords['updateOrder'], true),
                "Send Order Update Report has unexpected outcome for configured order at index {$key}."
            );
            $this->assertEquals(
                $config['expected']['reportUpdate'],
                $config['actual']['saveBasketUpdatesReported'],
                "Save Order Update Reported has unexpected outcome for configured order at index {$key}."
            );
        }
    }
}
