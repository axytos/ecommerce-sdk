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
     * @var \Axytos\ECommerce\DataTransferObjects\RefundBasketPositionDtoCollection|null
     */
    public $positions;
    /**
     * @var \Axytos\ECommerce\DataTransferObjects\RefundBasketTaxGroupDtoCollection|null
     */
    public $taxGroups;
}
