<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class CreateInvoiceBasketPositionDto implements DtoInterface
{
    public ?string $productId = null;

    public ?string $productName = null;

    public ?int $quantity = null;

    public ?float $taxPercent = null;

    public ?float $netPricePerUnit = null;

    public ?float $grossPricePerUnit = null;

    public ?float $netPositionTotal = null;

    public ?float $grossPositionTotal = null;
}
