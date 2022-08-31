<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\PaymentControl;

abstract class PaymentControlAction
{
    public const COMPLETE_ORDER = 'COMPLETE_ORDER';
    public const CANCEL_ORDER = 'CANCEL_ORDER';
    public const CHANGE_PAYMENT_METHOD = 'CHANGE_PAYMENT_METHOD';
}
