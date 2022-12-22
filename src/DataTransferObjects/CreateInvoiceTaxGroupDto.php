<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class CreateInvoiceTaxGroupDto implements DtoInterface
{
    /**
     * @var float|null
     */
    public $taxPercent;
    /**
     * @var float|null
     */
    public $valueToTax;
    /**
     * @var float|null
     */
    public $total;
}
