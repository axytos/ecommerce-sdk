<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class PaymentControlBasketPositionDto implements DtoInterface
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
     * @var string|null
     */
    public $productCategory;

    /**
     * @var int|null
     */
    public $quantity;

    /**
     * @var float|null
     */
    public $taxPercent;

    /**
     * @var float|null
     */
    public $netPositionTotal;

    /**
     * @var float|null
     */
    public $grossPositionTotal;
}
