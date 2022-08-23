<?php declare(strict_types=1);

namespace Axytos\ECommerce\Clients\Invoice;

class InvoiceOrderPaymentUpdate
{
    public string $orderId;
    public string $paymentStatus;
}