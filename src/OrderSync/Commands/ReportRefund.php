<?php

namespace Axytos\ECommerce\OrderSync\Commands;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\OrderSync\OrderSyncCommandInterface;
use Axytos\ECommerce\OrderSync\ShopSystemOrderInterface;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;

class ReportRefund implements OrderSyncCommandInterface
{
    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface
     */
    private $invoiceClient;

    /**
     * @var \Axytos\ECommerce\Logging\LoggerAdapterInterface
     */
    private $logger;

    /**
     * @var \Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface
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
     * @return void
     */
    public function execute($shopSystemOrder)
    {
        if ($shopSystemOrder->hasRefundReported()) {
            return;
        }

        if (!$shopSystemOrder->hasBeenRefunded()) {
            return;
        }

        if (!$shopSystemOrder->hasCreateInvoiceReported()) {
            return;
        }

        $this->logger->info('Order: ' . $shopSystemOrder->getOrderNumber() . ' | ReportRefund started');

        try {
            $this->invoiceClient->refund($shopSystemOrder->getRefundReportData());
        } catch (ApiException $exception) {
            if ($exception->getCode() >= 400 && $exception->getCode() < 500) {
                $this->errorReportingClient->reportError($exception);
                $this->logger->warning('Order: ' . $shopSystemOrder->getOrderNumber() . ' | ' . $exception);
            } else {
                throw $exception;
            }
        }

        $shopSystemOrder->saveHasRefundReported();

        $this->logger->info('Order: ' . $shopSystemOrder->getOrderNumber() . ' | ReportRefund finished');
    }
}
