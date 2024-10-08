<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class CreateInvoiceBasketDto implements DtoInterface
{
    /**
     * @var float|null
     */
    public $netTotal;
    /**
     * @var float|null
     */
    public $grossTotal;
    /**
     * @var CreateInvoiceBasketPositionDtoCollection
     */
    public $positions;
    /**
     * @var CreateInvoiceTaxGroupDtoCollection
     */
    public $taxGroups;
}
