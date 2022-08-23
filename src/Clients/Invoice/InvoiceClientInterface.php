<?php declare(strict_types=1);

namespace Axytos\ECommerce\Clients\Invoice;

interface InvoiceClientInterface
{
    function precheck(InvoiceOrderContextInterface $orderContext): string;

    function confirmOrder(InvoiceOrderContextInterface $orderContext): void;

    function cancelOrder(InvoiceOrderContextInterface $orderContext): void;

    function createInvoice(InvoiceOrderContextInterface $orderContext): void;

    function reportShipping(InvoiceOrderContextInterface $orderContext): void;

    function refund(InvoiceOrderContextInterface $orderContext): void;

    function return(InvoiceOrderContextInterface $orderContext): void;

    function getInvoiceOrderPaymentUpdate(string $paymentId): InvoiceOrderPaymentUpdate;
}
