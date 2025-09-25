<?php

namespace Axytos\ECommerce\Clients\Invoice;

interface InvoiceClientInterface
{
    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return string
     */
    public function precheck($orderContext);

    /**
     * @param InvoiceOrderContextInterface $orderContext
     * @param bool                         $skipPrecheck
     *
     * @return void
     */
    public function confirmOrder($orderContext, $skipPrecheck = true);

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function cancelOrder($orderContext);

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function uncancelOrder($orderContext);

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function createInvoice($orderContext);

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function reportShipping($orderContext);

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function trackingInformation($orderContext);

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function refund($orderContext);

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function returnOrder($orderContext);

    /**
     * @param string $paymentId
     *
     * @return InvoiceOrderPaymentUpdate
     */
    public function getInvoiceOrderPaymentUpdate($paymentId);

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function updateOrder($orderContext);

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return bool
     */
    public function hasBeenPaid($orderContext);
}
