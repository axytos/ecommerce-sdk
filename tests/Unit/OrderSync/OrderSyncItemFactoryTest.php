<?php

namespace Axytos\ECommerce\Tests\Unit\OrderSync;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\OrderSync\OrderSyncItem;
use Axytos\ECommerce\OrderSync\OrderSyncItemFactory;
use Axytos\ECommerce\OrderSync\ShopSystemOrderInterface;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class OrderSyncItemFactoryTest extends TestCase
{
    /**
     * @var OrderSyncItemFactory
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
        $this->sut = new OrderSyncItemFactory(
            $this->createMock(InvoiceClientInterface::class),
            $this->createMock(ErrorReportingClientInterface::class),
            $this->createMock(LoggerAdapterInterface::class)
        );
    }

    /**
     * @return void
     */
    public function test_create()
    {
        /** @var ShopSystemOrderInterface&MockObject */
        $shopSystemOrder = $this->createMock(ShopSystemOrderInterface::class);

        $orderSyncItem = $this->sut->create($shopSystemOrder);

        $this->assertInstanceOf(OrderSyncItem::class, $orderSyncItem);
    }

    /**
     * @return void
     */
    public function test_create_many()
    {
        /** @var ShopSystemOrderInterface[]&MockObject[] */
        $shopSystemOrders = [
            $this->createMock(ShopSystemOrderInterface::class),
            $this->createMock(ShopSystemOrderInterface::class),
            $this->createMock(ShopSystemOrderInterface::class),
            $this->createMock(ShopSystemOrderInterface::class),
        ];

        $orderSyncItems = $this->sut->createMany($shopSystemOrders);

        $this->assertCount(4, $orderSyncItems);

        foreach ($orderSyncItems as $orderSyncItem) {
            $this->assertInstanceOf(OrderSyncItem::class, $orderSyncItem);
        }
    }
}
