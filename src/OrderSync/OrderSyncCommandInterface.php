<?php

namespace Axytos\ECommerce\OrderSync;

interface OrderSyncCommandInterface
{
    /**
     * @param ShopSystemOrderInterface $shopSystemOrder
     * @return void
     */
    public function execute($shopSystemOrder);
}
