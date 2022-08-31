<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class CreateInvoiceTaxGroupDto implements DtoInterface
{
    public ?float $taxPercent = null;
    public ?float $valueToTax = null;
    public ?float $total = null;
}
