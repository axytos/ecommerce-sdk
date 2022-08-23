<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class RefundRequestDto implements DtoInterface
{
    public ?string $externalOrderId = null;
    public ?string $originalInvoiceNumber = null;
    public ?RefundBasketDto $basket = null;
}