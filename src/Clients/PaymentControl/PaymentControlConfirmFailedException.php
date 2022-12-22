<?php

namespace Axytos\ECommerce\Clients\PaymentControl;

use Exception;

class PaymentControlConfirmFailedException extends Exception
{
    /**
     * @param \Throwable $throwable
     */
    public function __construct($throwable)
    {
        parent::__construct("PaymentControl Confirm Failed!", 0, $throwable);
    }
}
