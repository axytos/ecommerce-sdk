<?php

namespace Axytos\ECommerce\OrderSync;

class OrderSyncItemRepository
{
    /**
     * @var ShopSystemOrderRepositoryInterface
     */
    private $shopSystemOrderRepository;

    /**
     * @var OrderSyncItemFactory
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
