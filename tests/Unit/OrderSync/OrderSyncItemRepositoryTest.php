<?php

namespace Axytos\ECommerce\Tests\Unit\OrderSync;

use Axytos\ECommerce\OrderSync\OrderSyncItemFactory;
use Axytos\ECommerce\OrderSync\OrderSyncItemInterface;
use Axytos\ECommerce\OrderSync\OrderSyncItemRepository;
use Axytos\ECommerce\OrderSync\ShopSystemOrderInterface;
use Axytos\ECommerce\OrderSync\ShopSystemOrderRepositoryInterface;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OrderSyncItemRepositoryTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\OrderSync\ShopSystemOrderRepositoryInterface&MockObject
     */
    private $shopSystemOrderRepository;

    /**
     * @var \Axytos\ECommerce\OrderSync\OrderSyncItemFactory&MockObject
     */
    private $orderSyncItemFactory;

    /**
     * @var \Axytos\ECommerce\OrderSync\OrderSyncItemRepository
     */
    private $sut;

    /**
     * @before
     * @return void
     */
    #[Before]
    public function beforeEach()
    {
        $this->shopSystemOrderRepository = $this->createMock(ShopSystemOrderRepositoryInterface::class);
        $this->orderSyncItemFactory = $this->createMock(OrderSyncItemFactory::class);

        $this->sut = new OrderSyncItemRepository(
            $this->shopSystemOrderRepository,
            $this->orderSyncItemFactory
        );
    }

    /**
     * @return void
     */
    public function test_getOrdersToSync()
    {
        /** @var ShopSystemOrderInterface[]&MockObject[] */
        $shopSystemOrders = [
            $this->createMock(ShopSystemOrderInterface::class),
            $this->createMock(ShopSystemOrderInterface::class)
        ];

        /** @var OrderSyncItemInterface[]&MockObject[] */
        $orderSyncItems = [
            $this->createMock(OrderSyncItemInterface::class),
            $this->createMock(OrderSyncItemInterface::class)
        ];

        $this->shopSystemOrderRepository->method('getOrdersToSync')->willReturn($shopSystemOrders);
        $this->orderSyncItemFactory->method('createMany')->with($shopSystemOrders)->willReturn($orderSyncItems);

        $actual = $this->sut->getOrdersToSync();

        $this->assertSame($orderSyncItems, $actual);
    }

    /**
     * @return void
     */
    public function test_getOrdersToUpdate()
    {
        /** @var ShopSystemOrderInterface[]&MockObject[] */
        $shopSystemOrders = [
            $this->createMock(ShopSystemOrderInterface::class),
            $this->createMock(ShopSystemOrderInterface::class)
        ];

        /** @var OrderSyncItemInterface[]&MockObject[] */
        $orderSyncItems = [
            $this->createMock(OrderSyncItemInterface::class),
            $this->createMock(OrderSyncItemInterface::class)
        ];

        $this->shopSystemOrderRepository->method('getOrdersToUpdate')->willReturn($shopSystemOrders);
        $this->orderSyncItemFactory->method('createMany')->with($shopSystemOrders)->willReturn($orderSyncItems);

        $actual = $this->sut->getOrdersToUpdate();

        $this->assertSame($orderSyncItems, $actual);
    }
}
