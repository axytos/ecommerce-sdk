<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class ReturnPositionModelDto implements DtoInterface
{
    public int $quantityToReturn;
    public string $productId;
}