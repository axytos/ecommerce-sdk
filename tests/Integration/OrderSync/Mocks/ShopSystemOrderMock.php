<?php

namespace Axytos\ECommerce\Tests\Integration\OrderSync\Mocks;

use Axytos\ECommerce\OrderSync\ShopSystemOrderInterface;

class ShopSystemOrderMock implements ShopSystemOrderInterface
{
    /**
     * @var string|int|null
     */
    private $orderNumber;

    /**
     * @var array<string,array<string,mixed>>
     */
    private $config;

    /**
     * @param string|int|null                   $orderNumber
     * @param array<string,array<string,mixed>> $config
     */
    public function __construct($orderNumber, $config)
    {
        $this->orderNumber = $orderNumber;
        $this->config = $config;

        $this->config['actual'] = [
            'saveHasCancelReported' => false,
            'saveHasCreateInvoiceReported' => false,
            'saveHasRefundReported' => false,
            'saveHasShippingReported' => false,
            'saveNewTrackingInformation' => false,
            'saveBasketUpdatesReported' => false,
            'savePartialRefundReported' => false,
        ];
    }

    /**
     * @return array<string,array<string,mixed>>
     */
    public function getTestConfig()
    {
        return $this->config;
    }

    /**
     * @return string|int|null
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    // ==================================================================================
    // Transaction
    // ==================================================================================

    /**
     * @return void
     */
    public function beginPersistenceTransaction()
    {
    }

    /**
     * @return void
     */
    public function commitPersistenceTransaction()
    {
    }

    /**
     * @return void
     */
    public function rollbackPersistenceTransaction()
    {
    }

    // ==================================================================================
    // CreateInvoice
    // ==================================================================================

    /**
     * @return bool
     */
    public function hasCreateInvoiceReported()
    {
        return isset($this->config['order']['hasCreateInvoiceReported'])
            ? (bool) $this->config['order']['hasCreateInvoiceReported']
            : false;
    }

    /**
     * @return void
     */
    public function saveHasCreateInvoiceReported()
    {
        $this->config['actual']['saveHasCreateInvoiceReported'] = true;
    }

    /**
     * @return bool
     */
    public function hasBeenInvoiced()
    {
        return isset($this->config['order']['hasBeenInvoiced'])
            ? (bool) $this->config['order']['hasBeenInvoiced']
            : false;
    }

    /**
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface
     */
    public function getCreateInvoiceReportData()
    {
        return new InvoiceOrderContextMock($this);
    }

    // ==================================================================================
    // Cancel
    // ==================================================================================

    /**
     * @return bool
     */
    public function hasCancelReported()
    {
        return isset($this->config['order']['hasCancelReported'])
            ? (bool) $this->config['order']['hasCancelReported']
            : false;
    }

    /**
     * @return void
     */
    public function saveHasCancelReported()
    {
        $this->config['actual']['saveHasCancelReported'] = true;
    }

    /**
     * @return bool
     */
    public function hasBeenCanceled()
    {
        return isset($this->config['order']['hasBeenCanceled'])
            ? (bool) $this->config['order']['hasBeenCanceled']
            : false;
    }

    /**
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface
     */
    public function getCancelReportData()
    {
        return new InvoiceOrderContextMock($this);
    }

    // ==================================================================================
    // Refund
    // ==================================================================================

    /**
     * @return bool
     */
    public function hasRefundReported()
    {
        return isset($this->config['order']['hasRefundReported'])
            ? (bool) $this->config['order']['hasRefundReported']
            : false;
    }

    /**
     * @return void
     */
    public function saveHasRefundReported()
    {
        $this->config['actual']['saveHasRefundReported'] = true;
    }

    /**
     * @return bool
     */
    public function hasBeenRefunded()
    {
        return isset($this->config['order']['hasBeenRefunded'])
            ? (bool) $this->config['order']['hasBeenRefunded']
            : false;
    }

    /**
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface
     */
    public function getRefundReportData()
    {
        return new InvoiceOrderContextMock($this);
    }

    // ==================================================================================
    // Partial Refund
    // ==================================================================================

    /**
     * @return bool
     */
    public function hasPartialRefundReported()
    {
        return isset($this->config['order']['hasPartialRefundReported'])
            ? (bool) $this->config['order']['hasPartialRefundReported']
            : false;
    }

    /**
     * @return void
     */
    public function savePartialRefundReported()
    {
        $this->config['actual']['savePartialRefundReported'] = true;
    }

    /**
     * @return bool
     */
    public function hasBeenPartialRefunded()
    {
        return isset($this->config['order']['hasBeenPartialRefunded'])
            ? (bool) $this->config['order']['hasBeenPartialRefunded']
            : false;
    }

    /**
     * @return bool
     */
    public function hasNewPartialRefundSinceLastReport()
    {
        return isset($this->config['order']['hasNewPartialRefundSinceLastReport'])
            ? (bool) $this->config['order']['hasNewPartialRefundSinceLastReport']
            : false;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getPartialRefundLastReportedAt()
    {
        $value = isset($this->config['order']['partialRefundLastReportedAt'])
            ? $this->config['order']['partialRefundLastReportedAt']
            : null;

        return ($value instanceof \DateTimeInterface) ? $value : null;
    }

    /**
     * @return void
     */
    public function savePartialRefundLastReportedAt(\DateTimeInterface $ts)
    {
        $this->config['order']['partialRefundLastReportedAt'] = $ts;
    }

    /**
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface
     */
    public function getPartialRefundReportData()
    {
        return new InvoiceOrderContextMock($this);
    }

    // ==================================================================================
    // Shipping
    // ==================================================================================

    /**
     * @return bool
     */
    public function hasShippingReported()
    {
        return isset($this->config['order']['hasShippingReported'])
            ? (bool) $this->config['order']['hasShippingReported']
            : false;
    }

    /**
     * @return void
     */
    public function saveHasShippingReported()
    {
        $this->config['actual']['saveHasShippingReported'] = true;
    }

    /**
     * @return bool
     */
    public function hasBeenShipped()
    {
        return isset($this->config['order']['hasBeenShipped'])
            ? (bool) $this->config['order']['hasBeenShipped']
            : false;
    }

    /**
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface
     */
    public function getShippingReportData()
    {
        return new InvoiceOrderContextMock($this);
    }

    // ==================================================================================
    // Tracking Information
    // ==================================================================================

    /**
     * @return bool
     */
    public function hasNewTrackingInformation()
    {
        return isset($this->config['order']['hasNewTrackingInformation'])
            ? (bool) $this->config['order']['hasNewTrackingInformation']
            : false;
    }

    /**
     * @return void
     */
    public function saveNewTrackingInformation()
    {
        $this->config['actual']['saveNewTrackingInformation'] = true;
    }

    /**
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface
     */
    public function getNewTrackingInformationReportData()
    {
        return new InvoiceOrderContextMock($this);
    }

    // ==================================================================================
    // Order Basket Updates
    // ==================================================================================

    /**
     * @return bool
     */
    public function hasBasketUpdates()
    {
        return isset($this->config['order']['hasBasketUpdates'])
            ? (bool) $this->config['order']['hasBasketUpdates']
            : false;
    }

    /**
     * @return void
     */
    public function saveBasketUpdatesReported()
    {
        $this->config['actual']['saveBasketUpdatesReported'] = true;
    }

    /**
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface
     */
    public function getBasketUpdateReportData()
    {
        return new InvoiceOrderContextMock($this);
    }
}
