<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class CreateInvoiceBasketDto implements DtoInterface
{
    public ?float $netTotal = null;
    public ?float $grossTotal = null;
    public CreateInvoiceBasketPositionDtoCollection $positions;
    public CreateInvoiceTaxGroupDtoCollection $taxGroups;
}
