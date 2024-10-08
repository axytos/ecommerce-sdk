<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class RefundBasketDto implements DtoInterface
{
    /**
     * @var float|null
     */
    public $grossTotal;
    /**
     * @var float|null
     */
    public $netTotal;
    /**
     * @var RefundBasketPositionDtoCollection|null
     */
    public $positions;
    /**
     * @var RefundBasketTaxGroupDtoCollection|null
     */
    public $taxGroups;
}
