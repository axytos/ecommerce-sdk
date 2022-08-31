<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class PaymentControlBasketDto implements DtoInterface
{
    public ?float $netTotal = null;
    public ?float $grossTotal = null;
    public ?string $currency = null;
    public PaymentControlBasketPositionDtoCollection $positions;
}
