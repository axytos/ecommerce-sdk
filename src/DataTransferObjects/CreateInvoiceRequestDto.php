<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;
use DateTimeInterface;

class CreateInvoiceRequestDto implements DtoInterface
{
    public ?string $externalOrderId = null;
    public ?string $externalInvoiceNumber = null;
    public ?string $externalSubOrderId = null;
    public ?int $dueDateOffsetDays = null;
    public ?CreateInvoiceBasketDto $basket = null;
}
