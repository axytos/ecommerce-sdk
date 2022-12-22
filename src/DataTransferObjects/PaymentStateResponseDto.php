<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class PaymentStateResponseDto implements DtoInterface
{
    /**
     * @var string|null
     */
    public $paymentState;
}
