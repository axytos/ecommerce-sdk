<?php

namespace Axytos\ECommerce\Clients\PaymentControl;

abstract class PaymentControlAction
{
    const COMPLETE_ORDER = 'COMPLETE_ORDER';
    const CANCEL_ORDER = 'CANCEL_ORDER';
    const CHANGE_PAYMENT_METHOD = 'CHANGE_PAYMENT_METHOD';
}
