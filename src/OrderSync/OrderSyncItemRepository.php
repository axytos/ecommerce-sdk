<?php

namespace Axytos\ECommerce\OrderSync;

use Axytos\ECommerce\OrderSync\OrderSyncItemFactory;
use Axytos\ECommerce\OrderSync\ShopSystemOrderRepositoryInterface;

class OrderSyncItemRepository
{
    /**
     * @var \Axytos\ECommerce\OrderSync\ShopSystemOrderRepositoryInterface
     */
    private $shopSystemOrderRepository;

    /**
     * @var \Axytos\ECommerce\OrderSync\OrderSyncItemFactory
     */
    private $orderSyncItemFactory;

    public function __construct(
        ShopSystemOrderRepositoryInterface $shopSystemOrderRepository,
        OrderSyncItemFactory $orderSyncItemFactory
    ) {
        $this->shopSystemOrderRepository = $shopSystemOrderRepository;
        $this->orderSyncItemFactory = $orderSyncItemFactory;
    }

    /**
     * @return \Axytos\ECommerce\OrderSync\OrderSyncItemInterface[]
     */
    public function getOrdersToSync()
    {
        $shopSystemOrders = $this->shopSystemOrderRepository->getOrdersToSync();
        return $this->orderSyncItemFactory->createMany($shopSystemOrders);
    }

    /**
     * @return \Axytos\ECommerce\OrderSync\OrderSyncItemInterface[]
     */
    public function getOrdersToUpdate()
    {
        $shopSystemOrders = $this->shopSystemOrderRepository->getOrdersToUpdate();
        return $this->orderSyncItemFactory->createMany($shopSystemOrders);
    }
}
