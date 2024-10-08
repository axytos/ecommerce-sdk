<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class RefundRequestDto implements DtoInterface
{
    /**
     * @var string|null
     */
    public $externalOrderId;
    /**
     * @var string|null
     */
    public $originalInvoiceNumber;
    /**
     * @var RefundBasketDto|null
     */
    public $basket;
}
