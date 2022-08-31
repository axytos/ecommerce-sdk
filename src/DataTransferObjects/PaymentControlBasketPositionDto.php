<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class PaymentControlBasketPositionDto implements DtoInterface
{
    public ?string $productId = null;

    public ?string $productName = null;

    public ?string $productCategory = null;

    public ?int $quantity = null;

    public ?float $taxPercent = null;

    public ?float $netPositionTotal = null;

    public ?float $grossPositionTotal = null;
}
