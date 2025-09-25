<?php

namespace Axytos\ECommerce\Tests\Integration\OrderSync\Mocks;

use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceOrderPaymentUpdate;

class InvoiceClientMock implements InvoiceClientInterface
{
    /**
     * @var array<string,InvoiceOrderContextInterface[]>
     */
    private $callRecords = [];

    public function __construct()
    {
        $this->callRecords = [
            'cancelOrder' => [],
            'uncancelOrder' => [],
            'createInvoice' => [],
            'refund' => [],
            'reportShipping' => [],
            'trackingInformation' => [],
            'updateOrder' => [],
        ];
    }

    /**
     * @return array<string,InvoiceOrderContextInterface[]>
     */
    public function getCallRecords()
    {
        return $this->callRecords;
    }

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return string
     */
    public function precheck($orderContext)
    {
        return '';
    }

    /**
     * @param InvoiceOrderContextInterface $orderContext
     * @param bool                         $skipPrecheck
     *
     * @return void
     */
    public function confirmOrder($orderContext, $skipPrecheck = true)
    {
    }

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function cancelOrder($orderContext)
    {
        $this->callRecords['cancelOrder'][] = $orderContext;
    }

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function uncancelOrder($orderContext)
    {
        $this->callRecords['uncancelOrder'][] = $orderContext;
    }

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function createInvoice($orderContext)
    {
        $this->callRecords['cancelOrder'][] = $orderContext;
    }

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function reportShipping($orderContext)
    {
        $this->callRecords['reportShipping'][] = $orderContext;
    }

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function trackingInformation($orderContext)
    {
        $this->callRecords['trackingInformation'][] = $orderContext;
    }

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function refund($orderContext)
    {
        $this->callRecords['refund'][] = $orderContext;
    }

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function returnOrder($orderContext)
    {
    }

    /**
     * @param string $paymentId
     *
     * @return InvoiceOrderPaymentUpdate
     */
    public function getInvoiceOrderPaymentUpdate($paymentId)
    {
        return new InvoiceOrderPaymentUpdate();
    }

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function updateOrder($orderContext)
    {
        $this->callRecords['updateOrder'][] = $orderContext;
    }

    /**
     * @param InvoiceOrderContextInterface $orderContext
     *
     * @return bool
     */
    public function hasBeenPaid($orderContext)
    {
        return false;
    }
}
