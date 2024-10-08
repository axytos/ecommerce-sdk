<?php

namespace Axytos\ECommerce\Clients\Checkout;

class CreditCheckAgreementLoadFailedException extends \Exception
{
    /**
     * @param \Throwable $throwable
     */
    public function __construct($throwable)
    {
        parent::__construct('CreditCheckAgreement Load Failed!', 0, $throwable);
    }
}
