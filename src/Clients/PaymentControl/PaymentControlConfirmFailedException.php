<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\PaymentControl;

use Exception;

class PaymentControlConfirmFailedException extends Exception
{
    public function __construct(\Throwable $throwable)
    {
        parent::__construct("PaymentControl Confirm Failed!", 0, $throwable);
    }
}
