<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class ReportShippingDto implements DtoInterface
{
    public ?string $externalOrderId = null;
    public ?ShippingBasketPositionDtoCollection $basketPositions = null;
}
