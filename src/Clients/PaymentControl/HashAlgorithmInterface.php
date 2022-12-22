<?php

namespace Axytos\ECommerce\Clients\PaymentControl;

interface HashAlgorithmInterface
{
    /**
     * @param string $input
     * @return string
     */
    public function compute($input);
}
