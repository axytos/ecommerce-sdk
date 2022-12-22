<?php

namespace Axytos\ECommerce\Clients\PaymentControl;

use Exception;

class PaymentControlCheckFailedException extends Exception
{
    /**
     * @param \Throwable $throwable
     */
    public function __construct($throwable)
    {
        parent::__construct("PaymentControl Check Failed!", 0, $throwable);
    }
}
