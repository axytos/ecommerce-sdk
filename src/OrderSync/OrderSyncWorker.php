<?php

namespace Axytos\ECommerce\OrderSync;

use Axytos\ECommerce\Logging\LoggerAdapterInterface;

class OrderSyncWorker
{
    /**
     * @var OrderSyncItemRepository
     */
    private $orderSyncItemRepository;

    /**
     * @var LoggerAdapterInterface
     */
    private $logger;

    public function __construct(
        OrderSyncItemRepository $orderSyncItemRepository,
        LoggerAdapterInterface $logger
    ) {
        $this->orderSyncItemRepository = $orderSyncItemRepository;
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function sync()
    {
        $this->logger->info('OrderSyncWorker started');
        $this->processUpdates();
        $this->processSync();
        $this->logger->info('OrderSyncWorker finished');
    }

    /**
     * @return void
     */
    private function processUpdates()
    {
        $orderSyncItems = $this->orderSyncItemRepository->getOrdersToUpdate();

        $this->logger->info('OrderSyncWorker: ' . count($orderSyncItems) . ' to update.');
        foreach ($orderSyncItems as $orderSyncItem) {
            $orderSyncItem->reportUpdate();
        }
    }

    /**
     * @return void
     */
    private function processSync()
    {
        $orderSyncItems = $this->orderSyncItemRepository->getOrdersToSync();

        $this->logger->info('OrderSyncWorker: ' . count($orderSyncItems) . ' to sync.');

        foreach ($orderSyncItems as $orderSyncItem) {
            $orderSyncItem->reportCancel();
            $orderSyncItem->reportCreateInvoice();
            $orderSyncItem->reportRefund();
            $orderSyncItem->reportShipping();
            $orderSyncItem->reportTrackingInformation();
        }
    }
}
