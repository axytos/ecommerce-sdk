<?php

namespace Axytos\ECommerce\OrderSync;

interface ShopSystemOrderRepositoryInterface
{
    /**
     * @return \Axytos\ECommerce\OrderSync\ShopSystemOrderInterface[]
     */
    public function getOrdersToSync();

    /**
     * @return \Axytos\ECommerce\OrderSync\ShopSystemOrderInterface[]
     */
    public function getOrdersToUpdate();
}
