<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;
use DateTimeInterface;

class ReturnRequestModelDto implements DtoInterface
{
    public string $externalOrderId;
    public ?string $externalSubOrderId = null;
    public ?DateTimeInterface $returnDate = null;
    public ?ReturnPositionModelDtoCollection $positions = null;
}
