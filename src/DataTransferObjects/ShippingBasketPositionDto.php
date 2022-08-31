<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class ShippingBasketPositionDto implements DtoInterface
{
    public ?string $productId = null;
    public ?int $quantity = null;
}
