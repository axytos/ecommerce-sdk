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
     * @var \Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketPositionDtoCollection
     */
    public $positions;
    /**
     * @var \Axytos\ECommerce\DataTransferObjects\CreateInvoiceTaxGroupDtoCollection
     */
    public $taxGroups;
}
