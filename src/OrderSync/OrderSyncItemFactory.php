<?php

namespace Axytos\ECommerce\OrderSync;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;

class OrderSyncItemFactory
{
    /**
     * @var InvoiceClientInterface
     */
    private $invoiceClient;

    /**
     * @var ErrorReportingClientInterface
     */
    private $errorReportingClient;

    /**
     * @var LoggerAdapterInterface
     */
    private $logger;

    public function __construct(
        InvoiceClientInterface $invoiceClient,
        ErrorReportingClientInterface $errorReportingClient,
        LoggerAdapterInterface $logger
    ) {
        $this->invoiceClient = $invoiceClient;
        $this->errorReportingClient = $errorReportingClient;
        $this->logger = $logger;
    }

    /**
     * @param ShopSystemOrderInterface $shopSystemOrder
     *
     * @return OrderSyncItemInterface
     */
    public function create($shopSystemOrder)
    {
        return new OrderSyncItem(
            $shopSystemOrder,
            $this->invoiceClient,
            $this->errorReportingClient,
            $this->logger
        );
    }

    /**
     * @param ShopSystemOrderInterface[] $shopSystemOrders
     *
     * @return OrderSyncItemInterface[]
     */
    public function createMany($shopSystemOrders)
    {
        return array_map([$this, 'create'], $shopSystemOrders);
    }
}
