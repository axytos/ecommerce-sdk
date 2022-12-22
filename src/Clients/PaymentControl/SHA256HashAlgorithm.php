<?php

namespace Axytos\ECommerce\Clients\PaymentControl;

class SHA256HashAlgorithm implements HashAlgorithmInterface
{
    /**
     * @param string $input
     * @return string
     */
    public function compute($input)
    {
        $input = (string) $input;
        return hash('sha256', $input);
    }
}
