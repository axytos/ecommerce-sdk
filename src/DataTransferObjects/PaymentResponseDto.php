<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;
use DateTimeImmutable;

class PaymentResponseDto implements DtoInterface
{
    public ?string $id = null;

    public ?DateTimeImmutable $date = null;

    public ?string $invoiceNumber;

    public ?string $externalOrderId;

    public ?float $amount = null;

    public ?string $currency;
}