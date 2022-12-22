<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class RefundBasketPositionDto implements DtoInterface
{
    /**
     * @var string|null
     */
    public $productId;
    /**
     * @var float|null
     */
    public $netRefundTotal;
    /**
     * @var float|null
     */
    public $grossRefundTotal;
}
