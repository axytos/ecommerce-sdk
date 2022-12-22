<?php

namespace Axytos\ECommerce\Clients\Invoice;

abstract class PaymentStatus
{
    const UNPAID = 'Unpaid';
    const PARTIALLY_PAID = 'PartiallyPaid';
    const PAID = 'Paid';
    const OVERPAID = 'Overpaid';
}
