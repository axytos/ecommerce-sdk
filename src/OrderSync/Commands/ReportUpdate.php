<?php

namespace Axytos\ECommerce\OrderSync\Commands;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\OrderSync\OrderSyncCommandInterface;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;

class ReportUpdate implements OrderSyncCommandInterface
{
    /**
     * @var InvoiceClientInterface
     */
    private $invoiceClient;

    /**
     * @var LoggerAdapterInterface
     */
    private $logger;

    /**
     * @var ErrorReportingClientInterface
     */
    private $errorReportingClient;

    public function __construct(
        InvoiceClientInterface $invoiceClient,
        LoggerAdapterInterface $logger,
        ErrorReportingClientInterface $errorReportingClient
    ) {
        $this->invoiceClient = $invoiceClient;
        $this->logger = $logger;
        $this->errorReportingClient = $errorReportingClient;
    }

    /**
     * @param \Axytos\ECommerce\OrderSync\ShopSystemOrderInterface $shopSystemOrder
     *
     * @return void
     */
    public function execute($shopSystemOrder)
    {
        if ($shopSystemOrder->hasBeenCanceled()) {
            return;
        }

        if ($shopSystemOrder->hasCreateInvoiceReported()) {
            return;
        }

        if (!$shopSystemOrder->hasBasketUpdates()) {
            return;
        }
        $this->logger->info('Order: ' . $shopSystemOrder->getOrderNumber() . ' | ReportUpdate started');

        try {
            $this->invoiceClient->updateOrder($shopSystemOrder->getBasketUpdateReportData());
        } catch (ApiException $exception) {
            if ($exception->getCode() >= 400 && $exception->getCode() < 500) {
                $this->errorReportingClient->reportError($exception);
                $this->logger->warning('Order: ' . $shopSystemOrder->getOrderNumber() . ' | ' . $exception);
            } else {
                throw $exception;
            }
        }

        $shopSystemOrder->saveBasketUpdatesReported();

        $this->logger->info('Order: ' . $shopSystemOrder->getOrderNumber() . ' | ReportUpdate finished');
    }
}
