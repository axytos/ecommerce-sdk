<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class CreateInvoiceRequestDto implements DtoInterface
{
    /**
     * @var string|null
     */
    public $externalOrderId;
    /**
     * @var string|null
     */
    public $externalInvoiceNumber;
    /**
     * @var string|null
     */
    public $externalSubOrderId;
    /**
     * @var int|null
     */
    public $dueDateOffsetDays;
    /**
     * @var CreateInvoiceBasketDto|null
     */
    public $basket;
}
