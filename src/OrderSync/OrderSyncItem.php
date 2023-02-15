<?php

namespace Axytos\ECommerce\OrderSync;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\OrderSync\Commands\ReportCancel;
use Axytos\ECommerce\OrderSync\Commands\ReportCreateInvoice;
use Axytos\ECommerce\OrderSync\Commands\ReportRefund;
use Axytos\ECommerce\OrderSync\Commands\ReportShipping;
use Axytos\ECommerce\OrderSync\Commands\ReportTrackingInformation;
use Axytos\ECommerce\OrderSync\Commands\ReportUpdate;

class OrderSyncItem implements OrderSyncItemInterface
{
    /**
     * @var ShopSystemOrderInterface
     */
    private $shopSystemOrder;

    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface
     */
    private $invoiceClient;

    /**
     * @var \Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface
     */
    private $errorReportingClient;

    /**
     * @var \Axytos\ECommerce\Logging\LoggerAdapterInterface
     */
    private $logger;


    public function __construct(
        ShopSystemOrderInterface $shopSystemOrder,
        InvoiceClientInterface $invoiceClient,
        ErrorReportingClientInterface $errorReportingClient,
        LoggerAdapterInterface $logger
    ) {
        $this->shopSystemOrder = $shopSystemOrder;
        $this->invoiceClient = $invoiceClient;
        $this->errorReportingClient = $errorReportingClient;
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function reportCancel()
    {
        $this->execute(new ReportCancel(
            $this->invoiceClient,
            $this->logger,
            $this->errorReportingClient
        ));
    }

    /**
     * @return void
     */
    public function reportCreateInvoice()
    {
        $this->execute(new ReportCreateInvoice(
            $this->invoiceClient,
            $this->logger,
            $this->errorReportingClient
        ));
    }

    /**
     * @return void
     */
    public function reportRefund()
    {
        $this->execute(new ReportRefund(
            $this->invoiceClient,
            $this->logger,
            $this->errorReportingClient
        ));
    }

    /**
     * @return void
     */
    public function reportShipping()
    {
        $this->execute(new ReportShipping(
            $this->invoiceClient,
            $this->logger,
            $this->errorReportingClient
        ));
    }

    /**
     * @return void
     */
    public function reportTrackingInformation()
    {
        $this->execute(new ReportTrackingInformation(
            $this->invoiceClient,
            $this->logger,
            $this->errorReportingClient
        ));
    }

    /**
     * @return void
     */
    public function reportUpdate()
    {
        $this->execute(new ReportUpdate(
            $this->invoiceClient,
            $this->logger,
            $this->errorReportingClient
        ));
    }

    /**
     * @param OrderSyncCommandInterface $command
     * @return void
     */
    public function execute($command)
    {
        try {
            $this->shopSystemOrder->beginPersistenceTransaction();
            $command->execute($this->shopSystemOrder);
            $this->shopSystemOrder->commitPersistenceTransaction();
        } catch (\Throwable $th) {
            $this->errorReportingClient->reportError($th);
            $this->shopSystemOrder->rollbackPersistenceTransaction();
            $this->logger->error('Order: ' . $this->shopSystemOrder->getOrderNumber() . ' | ' . $th);
        } catch (\Exception $th) { // @phpstan-ignore-line because of php5 compatibility
            $this->errorReportingClient->reportError($th);
            $this->shopSystemOrder->rollbackPersistenceTransaction();
            $this->logger->error('Order: ' . $this->shopSystemOrder->getOrderNumber() . ' | ' . $th);
        }
    }
}
