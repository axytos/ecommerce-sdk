<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\Invoice;

interface InvoiceClientInterface
{
    public function precheck(InvoiceOrderContextInterface $orderContext): string;

    public function confirmOrder(InvoiceOrderContextInterface $orderContext): void;

    public function cancelOrder(InvoiceOrderContextInterface $orderContext): void;

    public function createInvoice(InvoiceOrderContextInterface $orderContext): void;

    public function reportShipping(InvoiceOrderContextInterface $orderContext): void;

    public function refund(InvoiceOrderContextInterface $orderContext): void;

    public function return(InvoiceOrderContextInterface $orderContext): void;

    public function getInvoiceOrderPaymentUpdate(string $paymentId): InvoiceOrderPaymentUpdate;
}
