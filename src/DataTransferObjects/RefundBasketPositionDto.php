<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class RefundBasketPositionDto implements DtoInterface
{
    public ?string $productId = null;
    public ?float $netRefundTotal = null;
    public ?float $grossRefundTotal = null;
}