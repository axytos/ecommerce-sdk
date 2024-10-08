<?php

namespace Axytos\ECommerce\Tests\Unit\OrderSync;

use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\OrderSync\OrderSyncItemInterface;
use Axytos\ECommerce\OrderSync\OrderSyncItemRepository;
use Axytos\ECommerce\OrderSync\OrderSyncWorker;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class OrderSyncWorkerTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\OrderSync\OrderSyncItemRepository&MockObject
     */
    private $orderSyncItemRepository;

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
        $this->orderSyncItemRepository = $this->createMock(OrderSyncItemRepository::class);

        $this->sut = new OrderSyncWorker(
            $this->orderSyncItemRepository,
            $this->createMock(LoggerAdapterInterface::class)
        );
    }

    /**
     * @return void
     */
    public function test_sync_executes_command_for_each_order_except_update()
    {
        /** @var OrderSyncItemInterface[]&MockObject[] */
        $orderSyncItems = [
            $this->createMock(OrderSyncItemInterface::class),
            $this->createMock(OrderSyncItemInterface::class),
            $this->createMock(OrderSyncItemInterface::class),
            $this->createMock(OrderSyncItemInterface::class),
            $this->createMock(OrderSyncItemInterface::class),
        ];

        $this->orderSyncItemRepository->method('getOrdersToUpdate')->willReturn([]);
        $this->orderSyncItemRepository->method('getOrdersToSync')->willReturn($orderSyncItems);

        $executionCounts = [
            'reportCancel' => 0,
            'reportCreateInvoice' => 0,
            'reportRefund' => 0,
            'reportShipping' => 0,
            'reportTrackingInformation' => 0,
            'reportUpdate' => 0,
        ];

        foreach ($orderSyncItems as $orderSyncItem) {
            $orderSyncItem->method('reportCancel')->willReturnCallback(function () use (&$executionCounts) {
                ++$executionCounts['reportCancel'];
            });
            $orderSyncItem->method('reportCreateInvoice')->willReturnCallback(function () use (&$executionCounts) {
                ++$executionCounts['reportCreateInvoice'];
            });
            $orderSyncItem->method('reportRefund')->willReturnCallback(function () use (&$executionCounts) {
                ++$executionCounts['reportRefund'];
            });
            $orderSyncItem->method('reportShipping')->willReturnCallback(function () use (&$executionCounts) {
                ++$executionCounts['reportShipping'];
            });
            $orderSyncItem->method('reportTrackingInformation')->willReturnCallback(function () use (&$executionCounts) {
                ++$executionCounts['reportTrackingInformation'];
            });
            $orderSyncItem->method('reportUpdate')->willReturnCallback(function () use (&$executionCounts) {
                ++$executionCounts['reportUpdate'];
            });
        }

        $this->sut->sync();

        $this->assertEquals(5, $executionCounts['reportCancel']);
        $this->assertEquals(5, $executionCounts['reportCreateInvoice']);
        $this->assertEquals(5, $executionCounts['reportRefund']);
        $this->assertEquals(5, $executionCounts['reportShipping']);
        $this->assertEquals(5, $executionCounts['reportTrackingInformation']);
        $this->assertEquals(0, $executionCounts['reportUpdate']);
    }

    /**
     * @return void
     */
    public function test_sync_executes_update_command_for_each_order()
    {
        /** @var OrderSyncItemInterface[]&MockObject[] */
        $orderSyncItems = [
            $this->createMock(OrderSyncItemInterface::class),
            $this->createMock(OrderSyncItemInterface::class),
            $this->createMock(OrderSyncItemInterface::class),
            $this->createMock(OrderSyncItemInterface::class),
            $this->createMock(OrderSyncItemInterface::class),
        ];

        $this->orderSyncItemRepository->method('getOrdersToSync')->willReturn([]);
        $this->orderSyncItemRepository->method('getOrdersToUpdate')->willReturn($orderSyncItems);

        $executionCounts = [
            'reportCancel' => 0,
            'reportCreateInvoice' => 0,
            'reportRefund' => 0,
            'reportShipping' => 0,
            'reportTrackingInformation' => 0,
            'reportUpdate' => 0,
        ];

        foreach ($orderSyncItems as $orderSyncItem) {
            $orderSyncItem->method('reportCancel')->willReturnCallback(function () use (&$executionCounts) {
                ++$executionCounts['reportCancel'];
            });
            $orderSyncItem->method('reportCreateInvoice')->willReturnCallback(function () use (&$executionCounts) {
                ++$executionCounts['reportCreateInvoice'];
            });
            $orderSyncItem->method('reportRefund')->willReturnCallback(function () use (&$executionCounts) {
                ++$executionCounts['reportRefund'];
            });
            $orderSyncItem->method('reportShipping')->willReturnCallback(function () use (&$executionCounts) {
                ++$executionCounts['reportShipping'];
            });
            $orderSyncItem->method('reportTrackingInformation')->willReturnCallback(function () use (&$executionCounts) {
                ++$executionCounts['reportTrackingInformation'];
            });
            $orderSyncItem->method('reportUpdate')->willReturnCallback(function () use (&$executionCounts) {
                ++$executionCounts['reportUpdate'];
            });
        }

        $this->sut->sync();

        $this->assertEquals(0, $executionCounts['reportCancel']);
        $this->assertEquals(0, $executionCounts['reportCreateInvoice']);
        $this->assertEquals(0, $executionCounts['reportRefund']);
        $this->assertEquals(0, $executionCounts['reportShipping']);
        $this->assertEquals(0, $executionCounts['reportTrackingInformation']);
        $this->assertEquals(5, $executionCounts['reportUpdate']);
    }
}
