<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class RefundBasketDto implements DtoInterface
{
    public ?float $grossTotal = null;
    public ?float $netTotal = null;
    public ?RefundBasketPositionDtoCollection $positions = null;
    public ?RefundBasketTaxGroupDtoCollection $taxGroups = null;
}