<?php

namespace Axytos\ECommerce\OrderSync;

interface ShopSystemOrderInterface
{
    /**
     * @return string|int|null
     */
    public function getOrderNumber();

    // ==================================================================================
    // Transaction
    // ==================================================================================

    /**
     * @return void
     */
    public function beginPersistenceTransaction();

    /**
     * @return void
     */
    public function commitPersistenceTransaction();

    /**
     * @return void
     */
    public function rollbackPersistenceTransaction();

    // ==================================================================================
    // CreateInvoice
    // ==================================================================================

    /**
     * @return bool
     */
    public function hasCreateInvoiceReported();

    /**
     * @return void
     */
    public function saveHasCreateInvoiceReported();

    /**
     * @return bool
     */
    public function hasBeenInvoiced();

    /**
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface
     */
    public function getCreateInvoiceReportData();

    // ==================================================================================
    // Cancel
    // ==================================================================================

    /**
     * @return bool
     */
    public function hasCancelReported();

    /**
     * @return void
     */
    public function saveHasCancelReported();

    /**
     * @return bool
     */
    public function hasBeenCanceled();

    /**
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface
     */
    public function getCancelReportData();

    // ==================================================================================
    // Refund
    // ==================================================================================

    /**
     * @return bool
     */
    public function hasRefundReported();

    /**
     * @return void
     */
    public function saveHasRefundReported();

    /**
     * @return bool
     */
    public function hasBeenRefunded();

    /**
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface
     */
    public function getRefundReportData();

    // ==================================================================================
    // Partial Refund
    // ==================================================================================

    /**
     * Check if the order has been partially refunded.
     *
     * @return bool
     */
    public function hasBeenPartialRefunded();

    /**
     * Check if new partial refunds occurred since the last report.
     *
     * @return bool
     */
    public function hasNewPartialRefundSinceLastReport();

    /**
     * Return timestamp of last partial refund reporting.
     *
     * @return \DateTimeInterface|null
     */
    public function getPartialRefundLastReportedAt();

    /**
     * Mark the latest partial refund reporting time.
     *
     * @return void
     */
    public function savePartialRefundLastReportedAt(\DateTimeInterface $ts);

    /**
     * Indicates if a partial refund has already been reported.
     *
     * @return bool
     */
    public function hasPartialRefundReported();

    /**
     * Mark partial refund as reported.
     *
     * @return void
     */
    public function savePartialRefundReported();

    /**
     * Return data necessary for partial refund reporting.
     *
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface
     */
    public function getPartialRefundReportData();

    // ==================================================================================
    // Shipping
    // ==================================================================================

    /**
     * @return bool
     */
    public function hasShippingReported();

    /**
     * @return void
     */
    public function saveHasShippingReported();

    /**
     * @return bool
     */
    public function hasBeenShipped();

    /**
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface
     */
    public function getShippingReportData();

    // ==================================================================================
    // Tracking Information
    // ==================================================================================

    /**
     * @return bool
     */
    public function hasNewTrackingInformation();

    /**
     * @return void
     */
    public function saveNewTrackingInformation();

    /**
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface
     */
    public function getNewTrackingInformationReportData();

    // ==================================================================================
    // Order Basket Updates
    // ==================================================================================

    /**
     * @return bool
     */
    public function hasBasketUpdates();

    /**
     * @return void
     */
    public function saveBasketUpdatesReported();

    /**
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface
     */
    public function getBasketUpdateReportData();
}
