<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class CreateInvoiceBasketPositionDto implements DtoInterface
{
    /**
     * @var string|null
     */
    public $productId;

    /**
     * @var string|null
     */
    public $productName;

    /**
     * @var float|null
     */
    public $quantity;

    /**
     * @var float|null
     */
    public $taxPercent;

    /**
     * @var float|null
     */
    public $netPricePerUnit;

    /**
     * @var float|null
     */
    public $grossPricePerUnit;

    /**
     * @var float|null
     */
    public $netPositionTotal;

    /**
     * @var float|null
     */
    public $grossPositionTotal;
}
