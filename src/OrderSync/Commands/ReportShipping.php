<?php

namespace Axytos\ECommerce\OrderSync\Commands;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\OrderSync\OrderSyncCommandInterface;
use Axytos\ECommerce\OrderSync\ShopSystemOrderInterface;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;

class ReportShipping implements OrderSyncCommandInterface
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
     * @param ShopSystemOrderInterface $shopSystemOrder
     *
     * @return void
     */
    public function execute($shopSystemOrder)
    {
        if ($shopSystemOrder->hasShippingReported()) {
            return;
        }

        if (!$shopSystemOrder->hasBeenShipped()) {
            return;
        }

        $this->logger->info('Order: ' . $shopSystemOrder->getOrderNumber() . ' | ReprotShipping started');

        try {
            $this->invoiceClient->reportShipping($shopSystemOrder->getShippingReportData());
        } catch (ApiException $exception) {
            if ($exception->getCode() >= 400 && $exception->getCode() < 500) {
                $this->errorReportingClient->reportError($exception);
                $this->logger->warning('Order: ' . $shopSystemOrder->getOrderNumber() . ' | ' . $exception);
            } else {
                throw $exception;
            }
        }

        $shopSystemOrder->saveHasShippingReported();

        $this->logger->info('Order: ' . $shopSystemOrder->getOrderNumber() . ' | ReprotShipping finished');
    }
}
