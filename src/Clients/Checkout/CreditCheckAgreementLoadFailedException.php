<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\Checkout;

use Exception;

class CreditCheckAgreementLoadFailedException extends Exception
{
    public function __construct(\Throwable $throwable)
    {
        parent::__construct("CreditCheckAgreement Load Failed!", 0, $throwable);
    }
}
