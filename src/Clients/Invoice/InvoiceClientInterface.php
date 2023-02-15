<?php

namespace Axytos\ECommerce\Clients\Invoice;

interface InvoiceClientInterface
{
    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return string
     */
    public function precheck($orderContext);

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function confirmOrder($orderContext);

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function cancelOrder($orderContext);

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function createInvoice($orderContext);

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function reportShipping($orderContext);

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function trackingInformation($orderContext);

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function refund($orderContext);

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function returnOrder($orderContext);

    /**
     * @param string $paymentId
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderPaymentUpdate
     */
    public function getInvoiceOrderPaymentUpdate($paymentId);

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function updateOrder($orderContext);
}
