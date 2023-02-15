<?php

namespace Axytos\ECommerce\OrderSync;

interface HashAlgorithmInterface
{
    /**
     * @param string $input
     * @return string
     */
    public function compute($input);
}
