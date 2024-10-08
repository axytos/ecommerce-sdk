<?php

namespace Axytos\ECommerce\OrderSync\Commands;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\OrderSync\OrderSyncCommandInterface;
use Axytos\ECommerce\OrderSync\ShopSystemOrderInterface;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;

class ReportTrackingInformation implements OrderSyncCommandInterface
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
        if (!$shopSystemOrder->hasNewTrackingInformation()) {
            return;
        }

        $this->logger->info('Order: ' . $shopSystemOrder->getOrderNumber() . ' | ReportTrackingInformation started');

        try {
            $trackingInformation = $shopSystemOrder->getNewTrackingInformationReportData();
            $this->logger->info('TrackingIds: ' . implode(',', $trackingInformation->getTrackingIds()));
            $this->logger->info('Logistician: ' . $trackingInformation->getLogistician());
            $this->invoiceClient->trackingInformation($trackingInformation);
        } catch (ApiException $exception) {
            if ($exception->getCode() >= 400 && $exception->getCode() < 500) {
                $this->errorReportingClient->reportError($exception);
                $this->logger->warning('Order: ' . $shopSystemOrder->getOrderNumber() . ' | ' . $exception);
            } else {
                throw $exception;
            }
        }

        $shopSystemOrder->saveNewTrackingInformation();

        $this->logger->info('Order: ' . $shopSystemOrder->getOrderNumber() . ' | ReportTrackingInformation finished');
    }
}
