<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\Invoice;

abstract class PaymentStatus
{
    public const UNPAID = 'Unpaid';
    public const PARTIALLY_PAID = 'PartiallyPaid';
    public const PAID = 'Paid';
    public const OVERPAID = 'Overpaid';
}
