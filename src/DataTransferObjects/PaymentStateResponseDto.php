<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class PaymentStateResponseDto implements DtoInterface
{
    public ?string $paymentState = null;
}
